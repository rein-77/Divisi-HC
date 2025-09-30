/**
 * Multi-Select Dropdown Component
 * Digunakan untuk memilih multiple options dari dropdown menggunakan Alpine.js
 * 
 * @param {Array} options - Array of objects dengan struktur {id, name}
 * @param {Array} selectedIds - Array of selected IDs (default: [])
 * @returns {Object} Alpine.js component object
 * 
 * @example
 * <div x-data="multiSelect([{id: 1, name: 'Option 1'}], [])">
 *   <!-- Your dropdown HTML here -->
 * </div>
 */
window.multiSelect = function(options, selectedIds = []) {
    return {
        open: false,
        options: options,
        selected: Array.isArray(selectedIds) ? selectedIds : [],
        
        /**
         * Computed property untuk menampilkan text pada dropdown button
         * @returns {String} Text yang ditampilkan
         */
        get selectedText() {
            if (this.selected.length === 0) {
                return 'Pilih bagian/seksi...';
            } else if (this.selected.length === 1) {
                const option = this.options.find(opt => opt.id === this.selected[0]);
                return option ? option.name : 'Pilih bagian/seksi...';
            } else {
                return `${this.selected.length} bagian/seksi dipilih`;
            }
        },
        
        /**
         * Toggle selection dari option
         * @param {Number|String} id - ID dari option yang di-toggle
         */
        toggleOption(id) {
            const index = this.selected.indexOf(id);
            if (index === -1) {
                this.selected.push(id);
            } else {
                this.selected.splice(index, 1);
            }
        },
        
        /**
         * Select all options
         */
        selectAll() {
            this.selected = this.options.map(opt => opt.id);
        },
        
        /**
         * Clear all selections
         */
        clearAll() {
            this.selected = [];
        },
        
        /**
         * Check if specific option is selected
         * @param {Number|String} id - ID dari option
         * @returns {Boolean}
         */
        isSelected(id) {
            return this.selected.includes(id);
        },
        
        /**
         * Get count of selected items
         * @returns {Number}
         */
        get selectedCount() {
            return this.selected.length;
        }
    }
}
