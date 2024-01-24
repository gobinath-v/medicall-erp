<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class CategorySummary extends Component
{
    use WithPagination;

    public $paginationTheme = 'bootstrap';

    public $categoryId = null;

    #[Url(as:'pp')]
    public $perPage = 10;

    protected $listeners = [

        'deleteCategory' => 'deleteCategoryById',
    ];

    public function deleteCategoryById($categoryId)
    {

        $category = Category::find($categoryId);
        $category->update([
            'deleted_by' => getAuthData()->id,
        ]);
        if ($category) {
            $isDeleted = $category->delete();
            if ($isDeleted) {
                session()->flash("success", "Category deleted successfully");
                return redirect(route("category"));
            } else {
                session()->flash("error", "Unable to delete category");
                return;
            }
        }
    }

    public function changePageValue($perPageValue)
    {
        $this->perPage = $perPageValue;
        $this->resetPage();
    }

    public function mount(Request $request)
    {
        $this->categoryId = $request->categoryId;
    }

    public function render()
    {
        $categories = Category::orderBy('name')->paginate($this->perPage);
        return view('livewire.category-summary', [
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}
