@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Menu Management</h1>
    <!-- Add Menu Button -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuModal">Add Menu</button>
    </div>

    <!-- Add Menu Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.menu.store') }}">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="menuName" class="form-label">Menu Name</label>
                <input type="text" class="form-control" id="menuName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="menuDescription" class="form-label">Description</label>
                <textarea class="form-control" id="menuDescription" name="description"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    @if($menus->isEmpty())
        <div class="alert alert-info">No menus found.</div>
    @else
        @foreach($menus as $menu)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $menu->label ?? $menu->name }}</strong>
                    <div>
                        <!-- Edit Menu Button -->
                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->menus_id }}">Edit</button>
                        <!-- Delete Menu Form -->
                        <form action="{{ route('admin.menu.delete', $menu->menus_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this menu?')">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Add Category Button -->
                    <button class="btn btn-outline-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal{{ $menu->menus_id }}">Add Category</button>
                    <!-- Add Category Modal -->
                    <div class="modal fade" id="addCategoryModal{{ $menu->menus_id }}" tabindex="-1" aria-labelledby="addCategoryModalLabel{{ $menu->menus_id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.menu.category.store') }}">
                          @csrf
                          <input type="hidden" name="menus_id" value="{{ $menu->menus_id }}">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="addCategoryModalLabel{{ $menu->menus_id }}">Add Category</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="label" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    @if($menu->categories->isEmpty())
                        <div class="text-muted">No categories for this menu.</div>
                    @else
                        <ul>
                        @foreach($menu->categories as $category)
                            <li>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>{{ $category->label ?? $category->name }}</strong>
                                    <div>
                                        <!-- Edit Category Button -->
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->menu_categories_id }}">Edit</button>
                                        <!-- Delete Category Form -->
                                        <form action="{{ route('admin.menu.category.delete', $category->menu_categories_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- Add Subcategory Button -->
                                <button class="btn btn-outline-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal{{ $category->menu_categories_id }}">Add Subcategory</button>
                                <!-- Add Subcategory Modal -->
                                <div class="modal fade" id="addSubcategoryModal{{ $category->menu_categories_id }}" tabindex="-1" aria-labelledby="addSubcategoryModalLabel{{ $category->menu_categories_id }}" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.menu.subcategory.store') }}">
                                      @csrf
                                      <input type="hidden" name="menus_id" value="{{ $menu->menus_id }}">
                                      <input type="hidden" name="menu_categories_id" value="{{ $category->menu_categories_id }}">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="addSubcategoryModalLabel{{ $category->menu_categories_id }}">Add Subcategory</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="mb-3">
                                            <label class="form-label">Subcategory Name</label>
                                            <input type="text" class="form-control" name="label" required>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                                @if($category->subcategories && $category->subcategories->count())
                                    <ul>
                                    @foreach($category->subcategories as $subcategory)
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $subcategory->label ?? $subcategory->name }}</strong>
                                                <div>
                                                    <!-- Edit Subcategory Button -->
                                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal{{ $subcategory->menu_subcategories_id }}">Edit</button>
                                                    <!-- Delete Subcategory Form -->
                                                    <form action="{{ route('admin.menu.subcategory.delete', $subcategory->menu_subcategories_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this subcategory?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- Add Item Button (for subcategory) -->
                                            <button class="btn btn-outline-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#addItemModalSubcat{{ $subcategory->menu_subcategories_id }}">Add Item</button>
                                            <!-- Add Item Modal (for subcategory) -->
                                            <div class="modal fade" id="addItemModalSubcat{{ $subcategory->menu_subcategories_id }}" tabindex="-1" aria-labelledby="addItemModalSubcatLabel{{ $subcategory->menu_subcategories_id }}" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <form method="POST" action="{{ route('admin.menu.item.store') }}">
                                                  @csrf
                                                  <input type="hidden" name="menus_id" value="{{ $menu->menus_id }}">
                                                  <input type="hidden" name="menu_categories_id" value="{{ $category->menu_categories_id }}">
                                                  <input type="hidden" name="menu_subcategories_id" value="{{ $subcategory->menu_subcategories_id }}">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="addItemModalSubcatLabel{{ $subcategory->menu_subcategories_id }}">Add Item to Subcategory</h5>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <div class="mb-3">
                                                        <label class="form-label">Item Name</label>
                                                        <input type="text" class="form-control" name="name" required>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label class="form-label">Price</label>
                                                        <input type="number" class="form-control" name="price" step="0.01" required>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control" name="description" rows="2"></textarea>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label class="form-label">Currency</label>
                                                        <select name="currencies_id" class="form-control" required>
                                                          <option value="">Select Currency</option>
                                                          @foreach($currencies as $currency)
                                                            <option value="{{ $currency->currencies_id }}">{{ $currency->currency_code }} ({{ $currency->name }})</option>
                                                          @endforeach
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                      <button type="submit" class="btn btn-primary">Add</button>
                                                    </div>
                                                  </div>
                                                </form>
                                              </div>
                                            </div>
                                            @if($subcategory->items && $subcategory->items->count())
                                                <ul>
                                                @foreach($subcategory->items as $item)
                                                    <li>
                                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start gap-2">
                                                            <span>{{ $item->label ?? $item->name }} - Price: {{ \App\Models\MenuItem::where('menus_id', $menu->menus_id)->where('items_id', $item->items_id)->value('price') }} {{ \App\Models\MenuItem::where('menus_id', $menu->menus_id)->where('items_id', $item->items_id)->join('currencies', 'menus_items.currencies_id', '=', 'currencies.currencies_id')->value('currency_symbol') }}</span>
                                                            <div class="d-flex gap-1">
                                                                <!-- Edit Item Button -->
                                                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->items_id }}">Edit</button>
                                                                <!-- Delete Item Form -->
                                                                <form action="{{ route('admin.menu.item.delete', $item->items_id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- Edit Item Modal -->
                                                        <div class="modal fade" id="editItemModal{{ $item->items_id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->items_id }}" aria-hidden="true">
                                                          <div class="modal-dialog">
                                                            <form method="POST" action="{{ route('admin.menu.item.update', $item->items_id) }}">
                                                              @csrf
                                                              @method('PUT')
                                                              <input type="hidden" name="menus_id" value="{{ $menu->menus_id }}">
                                                              <input type="hidden" name="menu_categories_id" value="{{ $category->menu_categories_id }}">
                                                              <input type="hidden" name="menu_subcategories_id" value="{{ $subcategory->menu_subcategories_id }}">
                                                              <div class="modal-content">
                                                                <div class="modal-header">
                                                                  <h5 class="modal-title" id="editItemModalLabel{{ $item->items_id }}">Edit Item</h5>
                                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                  <div class="mb-3">
                                                                    <label class="form-label">Item Name</label>
                                                                    <input type="text" class="form-control" name="name" value="{{ $item->label ?? $item->name }}" required>
                                                                  </div>
                                                                  <div class="mb-3">
                                                                    <label class="form-label">Price</label>
                                                                    <input type="number" class="form-control" name="price" step="0.01" value="{{ \App\Models\MenuItem::where('menus_id', $menu->menus_id)->where('items_id', $item->items_id)->value('price') }}" required>
                                                                  </div>
                                                                  <div class="mb-3">
                                                                    <label class="form-label">Description</label>
                                                                    <textarea class="form-control" name="description" rows="2">{{ $item->description ?? '' }}</textarea>
                                                                  </div>
                                                                  <div class="mb-3">
                                                                    <label class="form-label">Currency</label>
                                                                    <select name="currencies_id" class="form-control" required>
                                                                      <option value="">Select Currency</option>
                                                                      @foreach($currencies as $currency)
                                                                        <option value="{{ $currency->currencies_id }}" {{ \App\Models\MenuItem::where('menus_id', $menu->menus_id)->where('items_id', $item->items_id)->value('currencies_id') == $currency->currencies_id ? 'selected' : '' }}>{{ $currency->currency_code }} ({{ $currency->name }})</option>
                                                                      @endforeach
                                                                    </select>
                                                                  </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                  <button type="submit" class="btn btn-primary">Update</button>
                                                                </div>
                                                              </div>
                                                            </form>
                                                          </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @else
                                                <div class="text-muted">No items in this subcategory.</div>
                                            @endif
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                                @if($category->items && $category->items->count())
                                    <ul>
                                    @foreach($category->items as $item)
                                        <li>
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start gap-2">
                                                <span>{{ $item->label ?? $item->name }}</span>
                                                <div class="d-flex gap-1">
                                                    <!-- Edit Item Button -->
                                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->items_id }}">Edit</button>
                                                    <!-- Delete Item Form -->
                                                    <form action="{{ route('admin.menu.item.delete', $item->items_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection