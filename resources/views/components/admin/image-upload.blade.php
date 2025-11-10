<!-- resources/views/components/admin/image-upload.blade.php -->
<div class="space-y-4">
    <!-- Upload Area -->
    <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-6 text-center hover:border-accent transition">
        <input type="file" 
               name="images[]" 
               id="image-upload" 
               multiple 
               accept="image/*"
               class="hidden"
               onchange="handleImageUpload(event)">
        
        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
        <p class="text-sm text-gray-600 dark:text-slate-400 mb-2">
            <label for="image-upload" class="text-accent cursor-pointer hover:text-accent/80">
                Klik untuk upload
            </label> atau drag & drop
        </p>
        <p class="text-xs text-gray-500 dark:text-slate-500">
            PNG, JPG, JPEG maksimal 2MB per gambar
        </p>
    </div>

    <!-- Image Preview -->
    <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Preview images will appear here -->
    </div>

    <!-- Hidden template for preview -->
    <template id="image-preview-template">
        <div class="relative group">
            <img class="w-full h-24 object-cover rounded-lg" src="" alt="Preview">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition rounded-lg flex items-center justify-center">
                <button type="button" 
                        onclick="removeImage(this)"
                        class="text-white opacity-0 group-hover:opacity-100 transition">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>
</div>

<script>
function handleImageUpload(event) {
    const files = event.target.files;
    const preview = document.getElementById('image-preview');
    
    for (let file of files) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const template = document.getElementById('image-preview-template');
                const clone = template.content.cloneNode(true);
                const img = clone.querySelector('img');
                img.src = e.target.result;
                preview.appendChild(clone);
            };
            reader.readAsDataURL(file);
        }
    }
}

function removeImage(button) {
    button.closest('.relative').remove();
}
</script>