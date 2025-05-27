@extends('admin.layouts.app')

@section('title', 'Menu Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Menu Management</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Select Menu</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <select class="form-select" name="menu_id" onchange="this.form.submit()">
                            @foreach($menus as $menu)
                                <option value="{{ $menu->menus_id }}" {{ request('menu_id', $selectedMenu->menus_id ?? null) == $menu->menus_id ? 'selected' : '' }}>
                                    {{ $menu->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 text-end">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal">Add Menu</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedMenu)
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Categories</span>
                    <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</a>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($categories as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $category->label }}</span>
                            <span>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->menu_categories_id }}">Edit</a>
                                <form action="{{ route('admin.menu.categories.destroy', $category) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                                </form>
                            </span>
                        </li>
                        <!-- Edit Category Modal -->
                        <div class="modal fade" id="editCategoryModal{{ $category->menu_categories_id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->menu_categories_id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.menu.categories.update', $category) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryModalLabel{{ $category->menu_categories_id }}">Edit Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" class="form-control" name="label" value="{{ $category->label }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Subcategories</span>
                    <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">Add Subcategory</a>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($subcategories as $subcategory)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $subcategory->label }}</span>
                            <span>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal{{ $subcategory->menu_subcategories_id }}">Edit</a>
                                <form action="{{ route('admin.menu.subcategories.destroy', $subcategory) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subcategory?')">Delete</button>
                                </form>
                            </span>
                        </li>
                        <!-- Edit Subcategory Modal -->
                        <div class="modal fade" id="editSubcategoryModal{{ $subcategory->menu_subcategories_id }}" tabindex="-1" aria-labelledby="editSubcategoryModalLabel{{ $subcategory->menu_subcategories_id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.menu.subcategories.update', $subcategory) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSubcategoryModalLabel{{ $subcategory->menu_subcategories_id }}">Edit Subcategory</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" class="form-control" name="label" value="{{ $subcategory->label }}" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Items</span>
                    <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</a>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $item->label }} <small class="text-muted">({{ $item->price }})</small></span>
                            <span>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->menu_items_id }}">Edit</a>
                                <form action="{{ route('admin.menu.items.destroy', $item) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                                </form>
                            </span>
                        </li>
                        <!-- Edit Item Modal -->
                        <div class="modal fade" id="editItemModal{{ $item->menu_items_id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->menu_items_id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.menu.items.update', $item) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editItemModalLabel{{ $item->menu_items_id }}">Edit Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" class="form-control mb-2" name="label" value="{{ $item->label }}" required>
                                            <input type="number" class="form-control mb-2" name="price" value="{{ $item->price }}" step="0.01" required>
                                            <textarea class="form-control" name="description" rows="2" placeholder="Description">{{ $item->description }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Menu Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.menu.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="label" placeholder="Menu Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.menu.categories.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="menus_id" value="{{ $selectedMenu->menus_id ?? '' }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="label" placeholder="Category Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Subcategory Modal -->
    <div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubcategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.menu.subcategories.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="menus_id" value="{{ $selectedMenu->menus_id ?? '' }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubcategoryModalLabel">Add Subcategory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="label" placeholder="Subcategory Name" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.menu.items.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="menus_id" value="{{ $selectedMenu->menus_id ?? '' }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control mb-2" name="label" placeholder="Item Name" required>
                        <input type="number" class="form-control mb-2" name="price" placeholder="Price" step="0.01" required>
                        <textarea class="form-control" name="description" rows="2" placeholder="Description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 