<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * 유효성 검사 실패 시 업로드된 파일을 임시 저장하고, 재제출 시 request에 복원합니다.
 */
class TempUploadSessionHelper
{
    private const DISK = 'local';
    private const PREFIX = 'temp_upload_session';

    /**
     * 요청에 포함된 파일을 임시 저장하고 세션에 경로·파일명을 저장합니다.
     *
     * @param  array<int, string>  $fileKeys  저장할 파일 필드명 목록 (예: ['business_registration'])
     */
    public static function saveToSession(Request $request, array $fileKeys, string $sessionKey): void
    {
        $saved = [];
        $dir = self::PREFIX . '/' . Str::slug($sessionKey);

        foreach ($fileKeys as $key) {
            $file = $request->file($key);
            if ($file && $file->isValid()) {
                $stored = $file->store($dir, self::DISK);
                $saved[$key] = ['path' => $stored, 'original_name' => $file->getClientOriginalName()];
            }
        }

        if ($saved !== []) {
            $request->session()->put($sessionKey, $saved);
        }
    }

    /**
     * 세션에 저장된 임시 파일을 request에 복원합니다.
     * clear_* 입력이 있으면 해당 키는 복원하지 않고 세션에서 제거합니다.
     */
    public static function restoreIntoRequest(Request $request, string $sessionKey): void
    {
        $data = $request->session()->get($sessionKey);
        if (! is_array($data)) {
            return;
        }

        foreach (array_keys($data) as $key) {
            if ($request->has('clear_' . $key)) {
                self::removeSessionFileKey($request, $sessionKey, $key);
                unset($data[$key]);
            }
        }

        foreach ($data as $key => $item) {
            if ($request->hasFile($key) && $request->file($key)?->isValid()) {
                continue;
            }
            $path = $item['path'] ?? null;
            $name = $item['original_name'] ?? 'file';
            if ($path && Storage::disk(self::DISK)->exists($path)) {
                $fullPath = Storage::disk(self::DISK)->path($path);
                $request->files->set($key, new UploadedFile($fullPath, $name, null, \UPLOAD_ERR_OK, true));
            }
        }
    }

    /**
     * 세션에 저장된 임시 파일 정보를 뷰 표시용으로 반환합니다.
     *
     * @return array<string, string>  필드명 => original_name
     */
    public static function getDisplayInfo(Request $request, string $sessionKey): array
    {
        $data = $request->session()->get($sessionKey);
        if (! is_array($data)) {
            return [];
        }
        $out = [];
        foreach ($data as $key => $item) {
            $out[$key] = $item['original_name'] ?? '';
        }
        return $out;
    }

    /**
     * 세션의 임시 파일을 삭제하고 세션 키를 제거합니다.
     */
    public static function clear(Request $request, string $sessionKey): void
    {
        $data = $request->session()->get($sessionKey);
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                $path = $item['path'] ?? null;
                if ($path) {
                    Storage::disk(self::DISK)->delete($path);
                }
            }
        }
        $request->session()->forget($sessionKey);
    }

    private static function removeSessionFileKey(Request $request, string $sessionKey, string $fileKey): void
    {
        $data = $request->session()->get($sessionKey);
        if (! is_array($data) || ! isset($data[$fileKey])) {
            return;
        }
        $path = $data[$fileKey]['path'] ?? null;
        if ($path) {
            Storage::disk(self::DISK)->delete($path);
        }
        unset($data[$fileKey]);
        $request->session()->put($sessionKey, $data);
    }
}
