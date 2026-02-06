<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteContentModel;

class Promo extends BaseController
{
    private $siteContentModel;

    public function __construct()
    {
        $this->siteContentModel = new SiteContentModel();
    }

    public function index()
    {
        // Fetch Promos
        $promos = $this->siteContentModel->where('section', 'promo')->orderBy('index_num', 'ASC')->findAll();

        return view('admin/promo/index', [
            'promos' => $promos
        ]);
    }

    public function update($id)
    {
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $file = $this->request->getFile('image');

        $data = [
            'title'   => $title,
            'content' => $content,
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/content', $newName);
            $data['image'] = $newName;
        }

        $this->siteContentModel->update($id, $data);

        return redirect()->to('/admin/promos')->with('success', 'Promo updated successfully.');
    }
}
