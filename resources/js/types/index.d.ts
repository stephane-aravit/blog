import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    role: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Post {
    id: number;
    title: string;
    content: string;
    user_id: number | null;
    created_at: string;
    updated_at: string;
    categories: Category[];
    comments: Comment[];
    user: User;
}

export interface Category {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
    posts: Post[];
}

export interface Comment {
    id: number;
    content: string;
    post_id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    post: Post;
    user: User;
}
