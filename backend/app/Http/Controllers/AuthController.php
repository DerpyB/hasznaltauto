<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==================== REGISZTRÁCIÓ ====================
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'type' => 'nullable|in:private,dealer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'type' => $request->type ?? 'private',
            'is_verified' => false,
        ]);

        // Generál és küld megerősítő kódot
        $code = $user->generateVerificationCode();
        $this->sendVerificationEmail($user, $code);

        return response()->json([
            'message' => 'Registration successful. Please check your email for verification code.',
            'user_id' => $user->id
        ], 201);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);

        if ($user->is_verified) {
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }

        if ($user->verifyCode($request->code)) {
            return response()->json([
                'message' => 'Email verified successfully. You can now log in.'
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid or expired verification code'
        ], 400);
    }

    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);

        if ($user->is_verified) {
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }

        $code = $user->generateVerificationCode();
        $this->sendVerificationEmail($user, $code);

        return response()->json([
            'message' => 'Verification code resent'
        ], 200);
    }

    // ==================== BEJELENTKEZÉS ====================

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->is_verified) {
            return response()->json([
                'message' => 'Email not verified. Please verify your email first.',
                'user_id' => $user->id,
                'needs_verification' => true
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    // ==================== ELFELEJTETT JELSZÓ ====================

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->generateResetToken();

        $this->sendPasswordResetEmail($user, $token);

        return response()->json([
            'message' => 'Password reset link sent to your email'
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:64',
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->verifyResetToken($request->token)) {
            return response()->json([
                'message' => 'Invalid or expired reset token'
            ], 400);
        }

        $user->resetPassword($request->password);

        return response()->json([
            'message' => 'Password reset successful. You can now log in.'
        ], 200);
    }

    public function validateResetToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:64',
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->verifyResetToken($request->token)) {
            return response()->json([
                'valid' => true
            ], 200);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Invalid or expired token'
        ], 400);
    }

    // ==================== JELSZÓVÁLTOZTATÁS ====================

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // Töröljük az összes tokent biztonsági okokból
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password changed successfully. Please log in again.'
        ], 200);
    }

    // ==================== SEGÉDFÜGGVÉNYEK ====================

    private function sendVerificationEmail(User $user, string $code): void
    {
        $subject = 'E-mail megerősítés - HasználtAutóPortál';
        $message = "Kedves {$user->name}!\n\n";
        $message .= "Köszönjük a regisztrációt. A fiókod aktiválásához használd a következő kódot:\n\n";
        $message .= "{$code}\n\n";
        $message .= "A kód 30 percig érvényes.\n\n";
        $message .= "Üdvözlettel,\nHasználtAutóPortál Csapat";

        Mail::raw($message, function ($mail) use ($user, $subject) {
            $mail->to($user->email)
                 ->subject($subject);
        });
    }

    private function sendPasswordResetEmail(User $user, string $token): void
    {
        $resetUrl = "https://hasznaltauto.alwaysdata.net/reset-password?token={$token}&email=" . urlencode($user->email);
        
        $subject = 'Jelszó-visszaállítás - HasználtAutóPortál';
        $message = "Kedves {$user->name}!\n\n";
        $message .= "Jelszó-visszaállítási kérelmet kaptunk. A jelszavad megváltoztatásához kattints az alábbi linkre:\n\n";
        $message .= "{$resetUrl}\n\n";
        $message .= "A link 60 percig érvényes.\n\n";
        $message .= "Ha nem te kérted a jelszó-visszaállítást, kérjük, hagyd figyelmen kívül ezt az e-mailt.\n\n";
        $message .= "Üdvözlettel,\nHasználtAutóPortál Csapat";

        Mail::raw($message, function ($mail) use ($user, $subject) {
            $mail->to($user->email)
                 ->subject($subject);
        });
    }
}