<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgrammingLanguageRequest;
use App\Models\ProgrammingLanguage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProgrammingLanguageController extends Controller
{
    public function index(): View
    {
        $languages = ProgrammingLanguage::withCount(['categories', 'quizzes'])
            ->orderBy('order_number')
            ->paginate(15);

        return view('admin.quiz.languages.index', compact('languages'));
    }

    public function create(): View
    {
        return view('admin.quiz.languages.form', [
            'language' => null,
            'action' => route('admin.programming-languages.store'),
            'method' => 'POST',
        ]);
    }

    public function store(ProgrammingLanguageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        unset($data['remove_logo']);

        $newLogo = null;
        if ($request->hasFile('logo')) {
            $newLogo = $request->file('logo')->store('programming-languages', 'public');
            $data['logo'] = $newLogo;
        }

        try {
            ProgrammingLanguage::create($data);
        } catch (Throwable $exception) {
            $this->deleteStoredLogo($newLogo);
            throw $exception;
        }

        return redirect()->route('admin.programming-languages.index')->with('success', 'Programming language created.');
    }

    public function edit(ProgrammingLanguage $programmingLanguage): View
    {
        return view('admin.quiz.languages.form', [
            'language' => $programmingLanguage,
            'action' => route('admin.programming-languages.update', $programmingLanguage),
            'method' => 'PUT',
        ]);
    }

    public function update(ProgrammingLanguageRequest $request, ProgrammingLanguage $programmingLanguage): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: $programmingLanguage->slug;
        $removeLogo = (bool) ($data['remove_logo'] ?? false);
        unset($data['remove_logo']);

        $oldLogo = $programmingLanguage->logo;
        $newLogo = null;
        if ($request->hasFile('logo')) {
            $newLogo = $request->file('logo')->store('programming-languages', 'public');
            $data['logo'] = $newLogo;
        } elseif ($removeLogo) {
            $data['logo'] = null;
        } else {
            unset($data['logo']);
        }

        try {
            $programmingLanguage->update($data);
        } catch (Throwable $exception) {
            $this->deleteStoredLogo($newLogo);
            throw $exception;
        }

        if ($newLogo || $removeLogo) {
            $this->deleteStoredLogo($oldLogo);
        }

        return redirect()->route('admin.programming-languages.index')->with('success', 'Programming language updated.');
    }

    public function destroy(ProgrammingLanguage $programmingLanguage): RedirectResponse
    {
        $logo = $programmingLanguage->logo;
        $programmingLanguage->delete();
        $this->deleteStoredLogo($logo);

        return back()->with('success', 'Programming language deleted.');
    }

    private function deleteStoredLogo(?string $logo): void
    {
        if ($logo && Str::startsWith($logo, 'programming-languages/')) {
            Storage::disk('public')->delete($logo);
        }
    }
}
