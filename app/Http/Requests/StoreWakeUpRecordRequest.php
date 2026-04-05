<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWakeUpRecordRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        // 認証済みユーザーのみ許可
        return $this->user() !== null;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        // 現在はPOSTパラメータなしだが、将来の拡張に備える
        return [];
    }

    /**
     * バリデーションエラーメッセージのカスタマイズ
     */
    public function messages(): array
    {
        return [];
    }
}
