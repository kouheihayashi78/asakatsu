export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    age?: number;
    target_wake_up_time?: string;
    introduction?: string;
    profile_image_path?: string;
    wake_up_achievements: number;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
