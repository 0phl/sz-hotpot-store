<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Edit Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/edit_item.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <input type="hidden" name="item_id" id="edit_item_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Item Name</label>
                        <input type="text" 
                               class="form-control" 
                               id="edit_name" 
                               name="name" 
                               required
                               maxlength="100"
                               autocomplete="off">
                        <div class="invalid-feedback">Please enter an item name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="edit_description" 
                                  name="description" 
                                  rows="3" 
                                  required
                                  style="min-height: 80px; resize: vertical;"></textarea>
                        <div class="invalid-feedback">Please enter a description.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price (₱)</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="edit_price" 
                                   name="price" 
                                   step="0.01" 
                                   required
                                   min="0">
                        </div>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Item Image</label>
                        <div class="image-upload-container">
                            <input type="file" 
                                   class="form-control" 
                                   id="edit_image" 
                                   name="image" 
                                   accept="image/*">
                            <small class="text-muted d-block mt-1">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Current Image</label>
                        <div class="current-image-container">
                            <img id="current_image" 
                                 src="" 
                                 alt="Current Item Image" 
                                 class="img-thumbnail current-item-image">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal-dialog {
    max-width: 500px;
}

.modal-content {
    border-radius: 0.5rem;
}

.modal-body {
    padding: 1.25rem;
}

.form-control {
    padding: 0.5rem 0.75rem;
    height: auto;
    line-height: 1.5;
}

.form-control:focus {
    box-shadow: none;
    border-color: #80bdff;
}

.current-image-container {
    text-align: center;
    margin-top: 0.5rem;
}

.current-item-image {
    max-width: 200px;
    max-height: 200px;
    object-fit: contain;
}

@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: none;
    }
    
    .modal-content {
        border-radius: 0.5rem;
        max-height: calc(100vh - 1rem);
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .current-item-image {
        max-width: 150px;
        max-height: 150px;
    }
}
</style>