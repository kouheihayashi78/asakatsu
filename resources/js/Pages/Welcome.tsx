import { PageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import {
    AlarmClock,
    ArrowRight,
    BarChart3,
    Check,
    Sunrise,
    Users,
} from 'lucide-react';

const features = [
    {
        icon: AlarmClock,
        title: 'ワンタップで起床記録',
        description:
            '目が覚めたらボタンを押すだけ。目標時間に間に合ったかを自動で判定します。',
    },
    {
        icon: BarChart3,
        title: '朝のリズムを見える化',
        description:
            '直近の記録や起床時間の分布から、自分の生活リズムを振り返れます。',
    },
    {
        icon: Users,
        title: '仲間と一緒に続ける',
        description:
            '同じ目標を持つ仲間を見つけて、ひとりでは難しい早起きを楽しく習慣に。',
    },
];

export default function Welcome({ auth }: PageProps) {
    return (
        <>
            <Head title="早起きできたねっ！" />

            <div className="min-h-screen overflow-hidden bg-[#fffaf1] text-slate-800">
                <div className="relative isolate">
                    <div className="absolute inset-x-0 top-0 -z-10 h-[46rem] bg-gradient-to-b from-amber-100 via-orange-50 to-[#fffaf1]" />
                    <div className="absolute -left-24 top-32 -z-10 h-72 w-72 rounded-full bg-yellow-300/30 blur-3xl" />
                    <div className="absolute -right-20 top-8 -z-10 h-96 w-96 rounded-full bg-orange-300/30 blur-3xl" />

                    <header className="mx-auto flex max-w-6xl items-center justify-between px-6 py-6 lg:px-8">
                        <Link
                            href="/"
                            className="flex items-center gap-2 font-bold text-orange-950"
                        >
                            <span className="flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-500 text-white shadow-lg shadow-orange-200">
                                <Sunrise
                                    className="h-6 w-6"
                                    aria-hidden="true"
                                />
                            </span>
                            <span className="text-lg sm:text-xl">
                                早起きできたねっ！
                            </span>
                        </Link>

                        <nav
                            className="flex items-center gap-2 sm:gap-3"
                            aria-label="メインナビゲーション"
                        >
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center gap-2 rounded-full bg-orange-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-orange-200 transition hover:bg-orange-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 sm:px-5"
                                >
                                    ダッシュボードへ
                                    <ArrowRight
                                        className="h-4 w-4"
                                        aria-hidden="true"
                                    />
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="rounded-full px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-white/70 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 sm:px-4"
                                    >
                                        ログイン
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="rounded-full bg-orange-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-orange-200 transition hover:bg-orange-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 sm:px-5"
                                    >
                                        無料で始める
                                    </Link>
                                </>
                            )}
                        </nav>
                    </header>

                    <main>
                        <section className="mx-auto grid max-w-6xl items-center gap-12 px-6 pb-24 pt-14 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:pb-32 lg:pt-24">
                            <div className="text-center lg:text-left">
                                <p className="mb-5 inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white/80 px-4 py-2 text-sm font-bold text-orange-700 shadow-sm backdrop-blur">
                                    <Sunrise
                                        className="h-4 w-4"
                                        aria-hidden="true"
                                    />
                                    今日の一歩が、明日の習慣になる
                                </p>
                                <h1 className="text-4xl font-black leading-tight tracking-tight text-orange-950 sm:text-5xl lg:text-6xl lg:leading-[1.15]">
                                    気持ちのいい朝を、
                                    <span className="mt-2 block text-orange-500">
                                        もっと続けやすく。
                                    </span>
                                </h1>
                                <p className="mx-auto mt-6 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg lg:mx-0">
                                    起きた時間を記録して、日々の変化を振り返る。
                                    仲間と励まし合いながら、早起きを無理なく習慣にするアプリです。
                                </p>

                                <div className="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row lg:justify-start">
                                    <Link
                                        href={
                                            auth.user
                                                ? route('dashboard')
                                                : route('register')
                                        }
                                        className="inline-flex w-full items-center justify-center gap-2 rounded-full bg-orange-500 px-7 py-4 font-bold text-white shadow-xl shadow-orange-200 transition hover:-translate-y-0.5 hover:bg-orange-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 sm:w-auto"
                                    >
                                        {auth.user
                                            ? '今日の記録を見る'
                                            : '早起きを始める'}
                                        <ArrowRight
                                            className="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </Link>
                                    {!auth.user && (
                                        <Link
                                            href={route('login')}
                                            className="inline-flex w-full items-center justify-center rounded-full border border-orange-200 bg-white/70 px-7 py-4 font-bold text-orange-900 transition hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 sm:w-auto"
                                        >
                                            アカウントをお持ちの方
                                        </Link>
                                    )}
                                </div>
                            </div>

                            <div className="relative mx-auto w-full max-w-md">
                                <div className="absolute inset-6 -z-10 rounded-full bg-orange-300/40 blur-3xl" />
                                <div className="rotate-2 rounded-[2rem] border border-white/80 bg-white/85 p-6 shadow-2xl shadow-orange-200/70 backdrop-blur sm:p-8">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className="text-sm font-bold text-slate-500">
                                                今日の目標
                                            </p>
                                            <p className="mt-1 text-3xl font-black text-slate-900">
                                                6:00
                                            </p>
                                        </div>
                                        <span className="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-orange-500">
                                            <AlarmClock
                                                className="h-8 w-8"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    </div>

                                    <div className="my-7 h-px bg-orange-100" />

                                    <div className="rounded-2xl bg-green-50 p-5 text-center">
                                        <span className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-500 text-white shadow-lg shadow-green-200">
                                            <Check
                                                className="h-7 w-7"
                                                strokeWidth={3}
                                                aria-hidden="true"
                                            />
                                        </span>
                                        <p className="mt-3 font-black text-green-800">
                                            目標時間までに起きられました！
                                        </p>
                                        <p className="mt-1 text-sm text-green-700">
                                            今日も素敵な一日の始まりです
                                        </p>
                                    </div>

                                    <div className="mt-6 grid grid-cols-3 gap-3 text-center">
                                        {[
                                            ['7日', '連続記録'],
                                            ['18回', '目標達成'],
                                            ['5:48', '平均起床'],
                                        ].map(([value, label]) => (
                                            <div
                                                key={label}
                                                className="rounded-xl bg-orange-50 px-2 py-3"
                                            >
                                                <p className="font-black text-orange-900">
                                                    {value}
                                                </p>
                                                <p className="mt-1 text-xs text-slate-500">
                                                    {label}
                                                </p>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section className="bg-white/80 py-20 sm:py-24">
                            <div className="mx-auto max-w-6xl px-6 lg:px-8">
                                <div className="mx-auto max-w-2xl text-center">
                                    <p className="font-bold text-orange-500">
                                        続けられる仕組み
                                    </p>
                                    <h2 className="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                                        朝の小さな達成を積み重ねよう
                                    </h2>
                                    <p className="mt-4 leading-7 text-slate-600">
                                        記録・振り返り・仲間とのつながりで、あなたの朝活を支えます。
                                    </p>
                                </div>

                                <div className="mt-12 grid gap-6 md:grid-cols-3">
                                    {features.map((feature) => {
                                        const Icon = feature.icon;

                                        return (
                                            <article
                                                key={feature.title}
                                                className="rounded-3xl border border-orange-100 bg-[#fffaf1] p-7 transition hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-100"
                                            >
                                                <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                                                    <Icon
                                                        className="h-6 w-6"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                                <h3 className="mt-5 text-xl font-black text-slate-900">
                                                    {feature.title}
                                                </h3>
                                                <p className="mt-3 text-sm leading-7 text-slate-600">
                                                    {feature.description}
                                                </p>
                                            </article>
                                        );
                                    })}
                                </div>
                            </div>
                        </section>

                        <section className="mx-auto max-w-6xl px-6 py-20 sm:py-24 lg:px-8">
                            <div className="overflow-hidden rounded-[2rem] bg-orange-950 px-6 py-12 text-center text-white shadow-2xl shadow-orange-200 sm:px-12 sm:py-16">
                                <Sunrise
                                    className="mx-auto h-10 w-10 text-amber-300"
                                    aria-hidden="true"
                                />
                                <h2 className="mt-5 text-3xl font-black sm:text-4xl">
                                    明日の朝から、始めてみませんか？
                                </h2>
                                <p className="mx-auto mt-4 max-w-xl leading-7 text-orange-100">
                                    まずは目標時間を決めて、起きた瞬間を記録するところから。
                                </p>
                                <Link
                                    href={
                                        auth.user
                                            ? route('dashboard')
                                            : route('register')
                                    }
                                    className="mt-8 inline-flex items-center gap-2 rounded-full bg-amber-300 px-7 py-4 font-black text-orange-950 transition hover:bg-amber-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-orange-950"
                                >
                                    {auth.user
                                        ? 'ダッシュボードへ'
                                        : '無料で始める'}
                                    <ArrowRight
                                        className="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </Link>
                            </div>
                        </section>
                    </main>

                    <footer className="border-t border-orange-100 px-6 py-8 text-center text-sm text-slate-500">
                        <p>© 早起きできたねっ！ 今日もいい朝にしよう。</p>
                    </footer>
                </div>
            </div>
        </>
    );
}
