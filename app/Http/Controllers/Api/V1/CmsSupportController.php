<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CmsSupportController extends Controller
{
    // 19.01 GET /api/v1/blogs
    public function blogs()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'title' => 'Top 10 Tips for Heart Health in 2026', 'category' => 'Cardiology', 'author' => 'Dr. Rajesh Sharma', 'date' => '2026-07-20', 'status' => 'Published'],
                ['id' => 2, 'title' => 'Understanding Pediatric Vaccines', 'category' => 'Pediatrics', 'author' => 'Dr. Sita Adhikari', 'date' => '2026-07-18', 'status' => 'Published'],
                ['id' => 3, 'title' => 'Nutrition Guide for Post-Op Patients', 'category' => 'Wellness', 'author' => 'NepXMedica Team', 'date' => '2026-07-15', 'status' => 'Draft'],
            ]
        ]);
    }

    // 19.03 GET /api/v1/blog-categories
    public function blogCategories()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'name' => 'Cardiology', 'posts_count' => 12],
                ['id' => 2, 'name' => 'Pediatrics', 'posts_count' => 8],
                ['id' => 3, 'name' => 'Neurology', 'posts_count' => 5],
                ['id' => 4, 'name' => 'Wellness & Lifestyle', 'posts_count' => 15],
            ]
        ]);
    }

    // 19.05 GET /api/v1/pages
    public function pages()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'title' => 'About Us', 'slug' => 'about-us', 'status' => 'Published'],
                ['id' => 2, 'title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'status' => 'Published'],
                ['id' => 3, 'title' => 'Terms & Conditions', 'slug' => 'terms-and-conditions', 'status' => 'Published'],
            ]
        ]);
    }

    // 19.07 GET /api/v1/faqs
    public function faqs()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'question' => 'How can I book an appointment online?', 'answer' => 'You can book an appointment using the Patient Portal or Mobile App.'],
                ['id' => 2, 'question' => 'What are the hospital visiting hours?', 'answer' => 'Visiting hours are 04:00 PM to 07:00 PM daily.'],
            ]
        ]);
    }

    // 20.01 GET /api/v1/tickets
    public function tickets()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 101, 'subject' => 'Cannot download PDF invoice', 'submitter' => 'Aayush Shrestha', 'priority' => 'High', 'status' => 'Open', 'date' => '2026-07-23'],
                ['id' => 102, 'subject' => 'Rescheduling appointment request', 'submitter' => 'Sunita Gurung', 'priority' => 'Normal', 'status' => 'Resolved', 'date' => '2026-07-22'],
            ]
        ]);
    }

    // 20.06 GET /api/v1/contact-messages
    public function contactMessages()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                ['id' => 1, 'name' => 'Ramesh Karki', 'email' => 'ramesh@nepxmedica.com', 'subject' => 'Corporate Health Checkup Inquiry', 'date' => '2026-07-23'],
            ]
        ]);
    }
}
