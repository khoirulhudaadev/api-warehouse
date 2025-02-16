<?php
  
namespace App\Http\Controllers\Api;
  
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Api\User;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTraitError;
use App\Traits\ApiResponseTraitSuccess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;  
  
class AuthWarehouseController extends Controller
{
 
    use ApiResponseTraitSuccess;
    use ApiResponseTraitError;

    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) 
    {

        $checkAccount = $this->userRepository->getByEmail($request->email);
        if(!$checkAccount) {
            return $this->sendApiError('Email tidak terdaftar!', $request->email, 422);
        }

        $checkPassword = Hash::check($request->password, $checkAccount->password);
        if(!$checkPassword) {
            return $this->sendApiError('Password tidak sesuai!', null, 422);
        }

        if (!$token = auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = [
            'user' => $checkAccount->load('role'),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 360
        ];
  
        return $this->sendApiResponse('Berhasil masuk!', $data);
    }

    /**
     * Send email message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        // Cek apakah email tersebut sudah terdaftar di database
        $checkAccount = $this->userRepository->getByEmail($request->email);
        if(!$checkAccount) {
            return $this->sendApiError('Email tidak terdaftar!', $request->email, 422);
        }
      
        // Generate token unik untuk reset password
        $token = Str::random(60);

        $findEmail = \DB::table('password_reset_tokens')
        ->where('email',$request->email)
        ->first();

        if($findEmail) {
            \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->update([
                'token' => $token,
                'created_at' => now(),
            ]);
        } else {
            \DB::table('password_reset_tokens')
            ->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);
        }
    
        try {
            Mail::to($request->email)->send(new PasswordResetMail($request->email, $token));
        } catch (\Exception $e) {
            return $this->sendApiError('Terjadi kesalahan saat mengirim email. Coba lagi nanti.', null, 500);
        }

        return $this->sendApiResponse('Periksa pesan email anda!', $request->email, 201);
    }

    public function resetPassword(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:8',
            ]);
            if($validator->fails()) 
            {
                return $this->sendApiError($validator->errors(), $request, 422);
            }

            $checkEmailAndToken = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token);
            if(!$checkEmailAndToken) {
                return $this->sendApiError('Token tidak valid atau sudah kadaluarsa!', $request->token, 400);
            };
            
            $user = User::where('email', $request->email)->first();
            if(!$user) {
                return $this->sendApiError('Email tidak terdaftar!', $request->email, 422);
            };

            $user->password = Hash::make($request->password);
            $user->save();

            \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

            return $this->sendApiResponse('Perbarui password telah berhasil!', $request->email, 201);
            
        } catch (ValidationException $e) {
            // Jika ada error validasi
            return $this->sendApiError('Validation error', $e->errors(), 422);
        } catch (Exception $e) {
            // Jika ada error lainnya (misalnya database atau lainnya)
            return $this->sendApiError('Terjadi kesalahan internal', $e->getMessage(), 500);
        }
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        
        return response()->json(['message' => 'Successfully logged out']);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->sendApiResponse('Berhasil masuk!', auth()->refresh());
    }
}