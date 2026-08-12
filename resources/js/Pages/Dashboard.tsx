import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { User, WakeUpRecord } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { CheckCircle, Clock, Sun } from 'lucide-react';
import { FormEventHandler } from 'react';
import {
    Cell,
    Legend,
    Pie,
    PieChart,
    Tooltip as RechartsTooltip,
    ResponsiveContainer,
} from 'recharts';

interface DashboardProps {
    todayRecord: WakeUpRecord | null;
    recentRecords: WakeUpRecord[];
    user: User;
}

export default function Dashboard({
    todayRecord,
    recentRecords,
    user,
}: DashboardProps) {
    const { post, processing } = useForm();

    const recordWakeUp: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('wake-up.store'));
    };

    // チャート用のデータを集計
    const generateChartData = () => {
        const bins: { [key: string]: number } = {
            '1:00-2:00': 0,
            '2:00-3:00': 0,
            '3:00-4:00': 0,
            '4:00-5:00': 0,
            '5:00-6:00': 0,
            '6:00-7:00': 0,
            '7:00-8:00': 0,
            '8:00-10:00': 0,
            '10:00以降': 0,
        };

        recentRecords.forEach((record) => {
            const date = new Date(record.recorded_at);
            const hours = date.getHours();
            if (hours >= 1 && hours < 2) bins['1:00-2:00']++;
            else if (hours >= 2 && hours < 3) bins['2:00-3:00']++;
            else if (hours >= 3 && hours < 4) bins['3:00-4:00']++;
            else if (hours >= 4 && hours < 5) bins['4:00-5:00']++;
            else if (hours >= 5 && hours < 6) bins['5:00-6:00']++;
            else if (hours >= 6 && hours < 7) bins['6:00-7:00']++;
            else if (hours >= 7 && hours < 8) bins['7:00-8:00']++;
            else if (hours >= 8 && hours < 10) bins['8:00-10:00']++;
            else bins['10:00以降']++;
        });

        return Object.keys(bins)
            .map((key) => ({ name: key, value: bins[key] }))
            .filter((data) => data.value > 0);
    };

    const chartData = generateChartData();
    const COLORS = [
        '#FF6384',
        '#36A2EB',
        '#FFCE56',
        '#4BC0C0',
        '#9966FF',
        '#FF9F40',
        '#E7E9ED',
        '#8D99AE',
        '#4D5360',
    ];

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    ダッシュボード（今日の記録と分析）
                </h2>
            }
        >
            <Head title="ダッシュボード" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    {/* 記録セクション */}
                    <div className="flex flex-col items-center justify-center overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                        <Sun className="mb-4 h-16 w-16 text-yellow-400" />
                        <h3 className="mb-2 text-2xl font-bold">
                            おはようございます！
                        </h3>
                        {todayRecord ? (
                            <div className="w-full max-w-md rounded-lg bg-green-50 p-4 text-center text-green-600">
                                <CheckCircle className="mx-auto mb-2" />
                                <p className="text-lg font-semibold">
                                    今日の起床は記録済みです！
                                </p>
                                <p className="mt-2 text-2xl font-bold">
                                    記録時間:{' '}
                                    {new Date(
                                        todayRecord.recorded_at,
                                    ).toLocaleTimeString('ja-JP')}
                                </p>
                                {user.target_wake_up_time ? (
                                    todayRecord.is_achieved ? (
                                        <p className="mt-3 inline-block rounded-full bg-green-200 px-3 py-1 text-sm font-bold text-green-800">
                                            🎉 目標時間までに起きられました！
                                        </p>
                                    ) : (
                                        <p className="mt-3 rounded-lg bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                                            今日は目標時間を過ぎました。記録できたことも一歩です。次回また頑張りましょう！
                                        </p>
                                    )
                                ) : (
                                    <p className="mt-3 rounded-lg bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-800">
                                        プロフィールで目標起床時間を設定して、達成を目指しましょう！
                                    </p>
                                )}
                            </div>
                        ) : (
                            <form
                                onSubmit={recordWakeUp}
                                className="w-full max-w-sm text-center"
                            >
                                <p className="mb-4 text-gray-600">
                                    目が覚めたらすぐにボタンを押して記録しましょう。
                                </p>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full transform rounded-full bg-yellow-400 px-8 py-4 text-xl font-bold text-white shadow-lg transition hover:bg-yellow-500 active:scale-95 disabled:opacity-50"
                                >
                                    今起きた！ (記録する)
                                </button>
                            </form>
                        )}
                    </div>

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {/* 履歴リストセクション */}
                        <div className="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                            <h3 className="mb-4 flex items-center border-b pb-2 text-lg font-bold">
                                <Clock className="mr-2 h-5 w-5 text-blue-500" />
                                直近1ヶ月の記録
                            </h3>
                            {recentRecords.length === 0 ? (
                                <p className="py-4 text-center text-gray-500">
                                    まだ記録がありません。
                                </p>
                            ) : (
                                <ul className="max-h-80 space-y-3 overflow-y-auto pr-2">
                                    {recentRecords.map((record) => (
                                        <li
                                            key={record.id}
                                            className="flex items-center justify-between rounded-lg bg-gray-50 p-3"
                                        >
                                            <span className="font-medium text-gray-700">
                                                {new Date(
                                                    record.recorded_at,
                                                ).toLocaleDateString('ja-JP', {
                                                    month: 'short',
                                                    day: 'numeric',
                                                    weekday: 'short',
                                                })}
                                            </span>
                                            <div className="flex items-center space-x-4">
                                                <span className="text-lg font-bold text-gray-900">
                                                    {new Date(
                                                        record.recorded_at,
                                                    ).toLocaleTimeString(
                                                        'ja-JP',
                                                        {
                                                            hour: '2-digit',
                                                            minute: '2-digit',
                                                        },
                                                    )}
                                                </span>
                                                {record.is_achieved ? (
                                                    <span className="rounded border border-green-300 bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                                        目標達成
                                                    </span>
                                                ) : (
                                                    <span className="rounded border border-amber-300 bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">
                                                        未達成
                                                    </span>
                                                )}
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>

                        {/* チャートセクション */}
                        <div className="flex flex-col overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                            <h3 className="mb-4 border-b pb-2 text-lg font-bold">
                                起床時間の分布 (直近1ヶ月)
                            </h3>
                            {chartData.length === 0 ? (
                                <p className="my-auto py-4 text-center text-gray-500">
                                    データが不足しています。
                                </p>
                            ) : (
                                <div className="h-64 min-h-[250px] w-full flex-1">
                                    <ResponsiveContainer
                                        width="100%"
                                        height="100%"
                                    >
                                        <PieChart>
                                            <Pie
                                                data={chartData}
                                                cx="50%"
                                                cy="50%"
                                                innerRadius={60}
                                                outerRadius={80}
                                                fill="#8884d8"
                                                paddingAngle={5}
                                                dataKey="value"
                                                label={({ name, percent }) =>
                                                    `${name} (${((percent || 0) * 100).toFixed(0)}%)`
                                                }
                                            >
                                                {chartData.map(
                                                    (entry, index) => (
                                                        <Cell
                                                            key={`cell-${index}`}
                                                            fill={
                                                                COLORS[
                                                                    index %
                                                                        COLORS.length
                                                                ]
                                                            }
                                                        />
                                                    ),
                                                )}
                                            </Pie>
                                            <RechartsTooltip />
                                            <Legend />
                                        </PieChart>
                                    </ResponsiveContainer>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
