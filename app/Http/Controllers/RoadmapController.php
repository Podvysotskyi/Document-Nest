<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class RoadmapController extends Controller
{
    public function __invoke(): View
    {
        return view('roadmap', [
            'phases' => [
                [
                    'label' => 'Current',
                    'title' => 'Private document vault',
                    'status' => 'In progress',
                    'items' => [
                        'Google-only sign in',
                        'Document upload, preview, download, edit, archive, and delete',
                        'Categories, tags, search, filters, and sorting',
                        'Expiry tracking dashboard sections',
                        'Private local file storage through authorized routes',
                    ],
                ],
                [
                    'label' => 'Next',
                    'title' => 'Document intelligence and reminders',
                    'status' => 'Planned',
                    'items' => [
                        'Email reminders for documents expiring soon',
                        'Saved filter views for common workflows',
                        'Bulk document actions',
                        'Document activity history',
                        'Improved mobile document preview experience',
                    ],
                ],
                [
                    'label' => 'Later',
                    'title' => 'Shared household workflows',
                    'status' => 'Exploring',
                    'items' => [
                        'Family or household access',
                        'Shared categories with per-user permissions',
                        'Document request checklists',
                        'Emergency access planning',
                        'Optional cloud storage drivers',
                    ],
                ],
                [
                    'label' => 'Future',
                    'title' => 'Automation and business use cases',
                    'status' => 'Long-term',
                    'items' => [
                        'OCR and extracted metadata',
                        'AI-assisted document classification',
                        'Small business workspaces',
                        'Compliance-oriented review workflows',
                        'Audit logs and approval flows',
                    ],
                ],
            ],
        ]);
    }
}
