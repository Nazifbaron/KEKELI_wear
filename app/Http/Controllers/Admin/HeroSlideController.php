<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class HeroSlideController extends Controller
{
    private function rules(): array
    {
        return [
            'tag'                 => 'required|string|max:60',
            'title'               => 'required|string|max:120',
            'title_highlight'     => 'nullable|string|max:80',
            'subtitle'            => 'nullable|string|max:300',
            'media_type'          => 'required|in:image,video',
            'btn_primary_label'   => 'nullable|string|max:60',
            'btn_primary_url'     => 'nullable|string|max:200',
            'btn_secondary_label' => 'nullable|string|max:60',
            'btn_secondary_url'   => 'nullable|string|max:200',
            'overlay_color'       => 'in:red,blue,purple',
            'is_active'           => 'boolean',
            'image'               => 'nullable|image|max:8192',
            // 50 Mo max — vérifier aussi upload_max_filesize / post_max_size dans php.ini
            'video'               => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
        ];
    }

    public function index()
    {
        $slides = HeroSlide::ordered()->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ($data['media_type'] === 'video' && !$request->hasFile('video')) {
            throw ValidationException::withMessages(['video' => 'Ajoutez un fichier vidéo (MP4 ou WebM).']);
        }

        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('hero', 'public');
        if ($data['media_type'] === 'video') {
            $data['video'] = $request->file('video')->store('hero/videos', 'public');
        } else {
            unset($data['video']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order']     = (HeroSlide::max('order') ?? 0) + 1; // ajoutée à la fin, réordonnable ensuite
        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides')->with('success', '✦ Slide "' . $data['tag'] . '" créée.');
    }

    public function update(Request $request, HeroSlide $slide)
    {
        $data = $request->validate($this->rules());

        if ($data['media_type'] === 'video' && !$request->hasFile('video') && !$slide->video) {
            throw ValidationException::withMessages(['video' => 'Ajoutez un fichier vidéo (MP4 ou WebM).']);
        }

        if ($request->hasFile('image')) {
            if ($slide->image) Storage::disk('public')->delete($slide->image);
            $data['image'] = $request->file('image')->store('hero', 'public');
        } else {
            unset($data['image']);
        }

        if ($request->hasFile('video')) {
            if ($slide->video) Storage::disk('public')->delete($slide->video);
            $data['video'] = $request->file('video')->store('hero/videos', 'public');
        } else {
            unset($data['video']); // on conserve la vidéo existante
        }

        $data['is_active'] = $request->boolean('is_active');
        $slide->update($data);

        return redirect()->route('admin.hero-slides')->with('success', '✦ Slide "' . $slide->tag . '" mise à jour.');
    }

    /* Réordonnancement par glisser-déposer : reçoit ids[] dans le nouvel ordre */
    public function reorder(Request $request): JsonResponse
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:hero_slides,id'])['ids'];
        foreach ($ids as $position => $id) {
            HeroSlide::where('id', $id)->update(['order' => $position + 1]);
        }
        return response()->json(['success' => true]);
    }

    public function toggle(HeroSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);
        return back()->with('success', 'Slide ' . ($slide->is_active ? 'activée' : 'désactivée') . '.');
    }

    public function destroy(HeroSlide $slide)
    {
        if ($slide->image) Storage::disk('public')->delete($slide->image);
        if ($slide->video) Storage::disk('public')->delete($slide->video);
        $slide->delete();
        return back()->with('success', 'Slide supprimée.');
    }
}
