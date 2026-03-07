import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { UserPlus, UserMinus, CheckCircle, Search } from 'lucide-react';

interface User {
    id: number;
    name: string;
    age: number | null;
    target_wake_up_time: string | null;
    wake_up_achievements: number;
    introduction: string | null;
    is_following: boolean;
    today_record: {
        recorded_at: string;
        is_achieved: boolean;
    } | null;
}

interface PaginationData {
    data: User[];
    current_page: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
    links: { url: string | null, label: string, active: boolean }[];
}

interface Props {
    users: PaginationData;
    filters: { filter: string };
    currentUserTargetTime: string | null;
}

export default function UsersIndex({ users, filters, currentUserTargetTime }: Props) {
    const handleFilterChange = (filter: string) => {
        router.get(route('users.index'), { filter }, { preserveState: true });
    };

    const toggleFollow = (userId: number, isFollowing: boolean) => {
        if (isFollowing) {
            router.delete(route('users.unfollow', userId), { preserveScroll: true });
        } else {
            router.post(route('users.follow', userId), {}, { preserveScroll: true });
        }
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">朝活仲間を見つける</h2>}
        >
            <Head title="ユーザー一覧" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                    {/* 絞り込みタブ */}
                    <div className="bg-white p-4 shadow-sm sm:rounded-lg flex space-x-4">
                        <button
                            onClick={() => handleFilterChange('all')}
                            className={`px-4 py-2 rounded-full font-bold text-sm ${filters.filter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`}
                        >
                            すべてのユーザー
                        </button>
                        <button
                            onClick={() => handleFilterChange('same_target')}
                            disabled={!currentUserTargetTime}
                            className={`px-4 py-2 rounded-full font-bold text-sm ${!currentUserTargetTime ? 'opacity-50 cursor-not-allowed' : filters.filter === 'same_target' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`}
                            title={!currentUserTargetTime ? 'プロフィール設定で目標起床時間を設定してください' : ''}
                        >
                            同じ目標時間 ({currentUserTargetTime || '未設定'})
                        </button>
                        <button
                            onClick={() => handleFilterChange('following')}
                            className={`px-4 py-2 rounded-full font-bold text-sm ${filters.filter === 'following' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`}
                        >
                            フォロー中
                        </button>
                    </div>

                    {/* ユーザー一覧 */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {users.data.map(user => (
                            <div key={user.id} className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-500 relative">
                                <div className="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 className="text-lg font-bold text-gray-900">{user.name}</h3>
                                        <p className="text-sm text-gray-500">
                                            {user.age ? `${user.age}歳` : '年齢非公開'}
                                        </p>
                                    </div>
                                    <button
                                        onClick={() => toggleFollow(user.id, user.is_following)}
                                        className={`p-2 rounded-full transition ${user.is_following ? 'bg-gray-100 text-gray-600 hover:bg-red-100 hover:text-red-600' : 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200'}`}
                                        title={user.is_following ? 'フォロー解除' : 'フォローする'}
                                    >
                                        {user.is_following ? <UserMinus size={20} /> : <UserPlus size={20} />}
                                    </button>
                                </div>

                                <div className="mb-4 text-sm text-gray-700 line-clamp-2 min-h-[40px]">
                                    {user.introduction || 'よろしくお願いします！'}
                                </div>

                                <div className="flex justify-between items-end border-t pt-4">
                                    <div>
                                        <p className="text-xs text-gray-500 mb-1">目標: {user.target_wake_up_time || '未設定'}</p>
                                        <p className="text-sm font-bold text-indigo-700">達成回数: {user.wake_up_achievements}回</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-xs text-gray-500 mb-1">今日の記録</p>
                                        {user.today_record ? (
                                            <div className="flex items-center space-x-1">
                                                <span className="font-bold">{new Date(user.today_record.recorded_at).toLocaleTimeString('ja-JP', { hour: '2-digit', minute: '2-digit' })}</span>
                                                {user.today_record.is_achieved && (
                                                    <CheckCircle className="w-4 h-4 text-green-500" />
                                                )}
                                            </div>
                                        ) : (
                                            <span className="text-gray-400 text-sm">未記録</span>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {users.data.length === 0 && (
                        <div className="bg-white p-12 text-center text-gray-500 shadow-sm sm:rounded-lg">
                            <Search className="mx-auto w-12 h-12 text-gray-300 mb-4" />
                            <p>該当するユーザーが見つかりません。</p>
                        </div>
                    )}

                    {/* ページネーション */}
                    {users.links.length > 3 && (
                        <div className="flex justify-center space-x-1 mt-6">
                            {users.links.map((link, i) => (
                                <button
                                    key={i}
                                    onClick={() => link.url && router.get(link.url, { filter: filters.filter }, { preserveState: true })}
                                    disabled={!link.url}
                                    className={`px-4 py-2 border rounded-md text-sm ${link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 hover:bg-gray-50'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    )}

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
