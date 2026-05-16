export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string | null;
    age?: number;
    target_wake_up_time?: string;
    introduction?: string;
    profile_image_path?: string;
    wake_up_achievements: number;
}

export interface WakeUpRecord {
    id: number;
    user_id: number;
    recorded_at: string;
    is_achieved: boolean;
    created_at: string;
    updated_at: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
