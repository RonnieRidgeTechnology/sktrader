<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('email', 'like', '%' . $term . '%');
        }

        $sort = $request->input('sort', 'subscribed_at');
        $dir = $request->input('dir', 'desc');
        if (!in_array($sort, ['email', 'subscribed_at', 'id'], true)) {
            $sort = 'subscribed_at';
        }
        if (!in_array($dir, ['asc', 'desc'], true)) {
            $dir = 'desc';
        }
        $query->orderBy($sort, $dir);

        $subscribers = $query->paginate(25)->withQueryString();

        return Inertia::render('Admin/Subscribers/Index', [
            'subscribers' => $subscribers,
            'filters' => [
                'search' => $request->input('search', ''),
                'sort' => $sort,
                'dir' => $dir,
            ],
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('email', 'like', '%' . $term . '%');
        }

        $sort = $request->input('sort', 'subscribed_at');
        $dir = $request->input('dir', 'desc');
        if (!in_array($sort, ['email', 'subscribed_at', 'id'], true)) {
            $sort = 'subscribed_at';
        }
        if (!in_array($dir, ['asc', 'desc'], true)) {
            $dir = 'desc';
        }
        $query->orderBy($sort, $dir);

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="subscribers-' . date('Y-m-d-His') . '.csv"',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Email', 'Subscribed at']);

            $query->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->email,
                        $row->subscribed_at?->toIso8601String() ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
