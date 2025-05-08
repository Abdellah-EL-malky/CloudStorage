<?php

namespace App\Http\Controllers;

use App\Models\Share;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShareController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $myShares = $user->shares()->latest()->get();

        $sharedWithMe = Share::where('recipient_id', $user->id)->latest()->get();

        return view('shares.index', compact('myShares', 'sharedWithMe'));
    }


    public function shareFile(Request $request, File $file)
    {
        $this->authorize('share', $file);

        $request->validate([
            'permission' => 'required|in:read,write,admin',
            'share_type' => 'required|in:link,user',
            'email' => 'required_if:share_type,user|nullable|email',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $user = Auth::user();
        $recipientId = null;

        if ($request->input('share_type') === 'user' && $request->has('email')) {
            $recipient = User::where('email', $request->input('email'))->first();
            if (!$recipient) {
                return back()->with('error', 'Aucun utilisateur trouvé avec cette adresse email.');
            }
            $recipientId = $recipient->id;
        }

        $share = Share::create([
            'user_id' => $user->id,
            'token' => Str::random(32),
            'shareable_type' => 'App\\Models\\File',
            'shareable_id' => $file->id,
            'permission' => $request->input('permission'),
            'recipient_id' => $recipientId,
            'expires_at' => $request->input('expires_at'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'share' => $share,
                'share_url' => $share->getShareUrl()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Fichier partagé avec succès.')
            ->with('share_url', $share->getShareUrl());
    }


    public function shareFolder(Request $request, Folder $folder)
    {
        $this->authorize('share', $folder);

        $request->validate([
            'permission' => 'required|in:read,write,admin',
            'share_type' => 'required|in:link,user',
            'email' => 'required_if:share_type,user|nullable|email',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $user = Auth::user();
        $recipientId = null;

        if ($request->input('share_type') === 'user' && $request->has('email')) {
            $recipient = User::where('email', $request->input('email'))->first();
            if (!$recipient) {
                return back()->with('error', 'Aucun utilisateur trouvé avec cette adresse email.');
            }
            $recipientId = $recipient->id;
        }

        $share = Share::create([
            'user_id' => $user->id,
            'token' => Str::random(32),
            'shareable_type' => 'App\\Models\\Folder',
            'shareable_id' => $folder->id,
            'permission' => $request->input('permission'),
            'recipient_id' => $recipientId,
            'expires_at' => $request->input('expires_at'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'share' => $share,
                'share_url' => $share->getShareUrl()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Dossier partagé avec succès.')
            ->with('share_url', $share->getShareUrl());
    }


    public function destroy(Share $share)
    {
        $this->authorize('delete', $share);

        $share->delete();

        return redirect()->route('shares.index')
            ->with('success', 'Partage supprimé avec succès.');
    }

    public function accessShare(string $token)
    {
        $share = Share::where('token', $token)->firstOrFail();

        if ($share->isExpired()) {
            return view('shares.expired');
        }

        $shareable = $share->shareable;

        if (!$shareable) {
            return view('shares.not-found');
        }

        if ($share->shareable_type === 'App\\Models\\File') {
            return view('shares.file', compact('share', 'shareable'));
        } else {
            $files = $shareable->files;
            return view('shares.folder', compact('share', 'shareable', 'files'));
        }
    }

    public function downloadSharedFile(string $token)
    {
        $share = Share::where('token', $token)->firstOrFail();

        if ($share->isExpired()) {
            return view('shares.expired');
        }

        if ($share->shareable_type !== 'App\\Models\\File') {
            abort(404);
        }

        $file = $share->shareable;

        if (!$file) {
            return view('shares.not-found');
        }

        return Storage::disk('local')->download($file->path, $file->name);
    }

    public function previewSharedFile(string $token)
    {
        $share = Share::where('token', $token)->firstOrFail();

        if ($share->isExpired()) {
            return view('shares.expired');
        }

        if ($share->shareable_type !== 'App\\Models\\File') {
            abort(404);
        }

        $file = $share->shareable;

        if (!$file) {
            return view('shares.not-found');
        }

        return response()->file(Storage::disk('local')->path($file->path));
    }

    public function downloadSharedFolderFile(string $token, int $fileId)
    {
        $share = Share::where('token', $token)->firstOrFail();

        if ($share->isExpired()) {
            return view('shares.expired');
        }

        if ($share->shareable_type !== 'App\\Models\\Folder') {
            abort(404);
        }

        $folder = $share->shareable;

        if (!$folder) {
            return view('shares.not-found');
        }

        $file = File::where('id', $fileId)
            ->where('folder_id', $folder->id)
            ->firstOrFail();

        return Storage::disk('local')->download($file->path, $file->name);
    }
}
