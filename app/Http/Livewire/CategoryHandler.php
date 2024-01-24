<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryHandler extends Component
{
    public $categoryId = null;

    public $category = [
        'type',
        'name',
        'description',
        'is_active' => 1,
        'is_default' => 0,

    ];

    protected $rules = [
        'category.name' => 'required|string',
        'category.type' => 'required',
    ];

    protected $messages = [
        'category.name.required' => 'Name is required',
        'category.type.required' => 'Type is required',
    ];

    public function create()
    {
        $this->validate();


        $categoryExists = Category::where('name', $this->category['name'])->first();
        if ($categoryExists) {
            $this->addError("category.name", "Name already exists");
            return;
        }
        $this->category['created_by'] = getAuthData()->id;
        $this->category['updated_by'] = getAuthData()->id;

        try {
            $category = Category::create($this->category);
            if ($category) {
                session()->flash('success', 'Category Successfully Created');
                return redirect(route('category'));
            }
            session()->flash('error', 'Error while creating category');
            return;
        } catch (\Exception $e) {
            session()->flash("error", $e->getMessage());
            return;
        }
    }

    public function update()
    {

        $this->validate();

        $categoryNameExists = Category::where('name', $this->category['name'])
            ->where('id', '!=', $this->category['id'])->first();

        if ($categoryNameExists) {
            $this->addError("category.name", "Name already exists");
            return;
        }

        try {
            $category = Category::find($this->categoryId);

            if ($category) {
                $this->category['updated_by'] = getAuthData()->id;
                $category->update($this->category);
                $isCategoryUpdated = $category->wasChanged('name', 'type', 'description', 'is_active');
                if ($isCategoryUpdated) {
                    session()->flash('success', 'Category Updated Successfully');
                    return redirect(route('category'));
                }
                session()->flash('info', 'Do Some Changes to be Updated');
                return ;
            }
            session()->flash('error', 'Error while creating category');
            return;
        } catch (\Exception $e) {
            session()->flash("error", $e->getMessage());
            return;
        }
    }

    public function resetFields()
    {
        $this->reset();
    }

    public function mount($categoryId = null)
    {
        $this->categoryId = $categoryId;
        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            $category ? $this->category = $category->toArray() : [];
            return;
        }
    }

    public function render()
    {
        return view('livewire.category-handler')->layout('layouts.admin');
    }
}