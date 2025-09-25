/**
 * Surat Keluar Form Handler
 * Handles dynamic form behavior for creating and editing Surat Keluar
 */

class SuratKeluarForm {
    constructor(config = {}) {
        this.config = {
            tujuanInputId: 'tujuan',
            bagianSeksiSearchId: 'bagian_seksi_search',
            bagianSeksiHiddenId: 'bagian_seksi_tujuan',
            bagianSeksiDropdownId: 'bagian_seksi_dropdown',
            unitKerjaDisplayId: 'unit_kerja_display',
            unitKerjaHiddenId: 'unit_kerja_tujuan',
            clearButtonId: 'clear_bagian_seksi',
            clearTujuanId: 'clear_tujuan',
            ...config
        };

        this.elements = {};
        this.bagianSeksiData = [];
        this.filteredBagianSeksi = [];
        
        this.init();
    }

    init() {
        // Get DOM elements
        this.getElements();
        
        // Check if required elements exist
        if (!this.validateElements()) {
            console.error('SuratKeluarForm: Required elements not found');
            return;
        }

        // Set bagian seksi data from global variable
        if (window.bagianSeksiData) {
            this.bagianSeksiData = window.bagianSeksiData;
            this.filteredBagianSeksi = [...this.bagianSeksiData];
        }

        // Bind events
        this.bindEvents();

        // Initialize form state
        this.initializeFormState();
    }

    getElements() {
        this.elements = {
            tujuanInput: document.getElementById(this.config.tujuanInputId),
            bagianSeksiSearch: document.getElementById(this.config.bagianSeksiSearchId),
            bagianSeksiHidden: document.getElementById(this.config.bagianSeksiHiddenId),
            bagianSeksiDropdown: document.getElementById(this.config.bagianSeksiDropdownId),
            unitKerjaDisplay: document.getElementById(this.config.unitKerjaDisplayId),
            unitKerjaHidden: document.getElementById(this.config.unitKerjaHiddenId),
            clearButton: document.getElementById(this.config.clearButtonId),
            clearTujuan: document.getElementById(this.config.clearTujuanId)
        };
    }

    validateElements() {
        const requiredElements = [
            'tujuanInput', 
            'bagianSeksiSearch', 
            'bagianSeksiHidden', 
            'bagianSeksiDropdown',
            'unitKerjaDisplay',
            'unitKerjaHidden'
        ];

        return requiredElements.every(elementKey => {
            if (!this.elements[elementKey]) {
                console.warn(`SuratKeluarForm: Element ${elementKey} not found`);
                return false;
            }
            return true;
        });
    }

    bindEvents() {
        // Tujuan input events
        this.elements.tujuanInput.addEventListener('input', () => {
            this.toggleFields();
        });

        // Clear button event
        if (this.elements.clearButton) {
            this.elements.clearButton.addEventListener('click', () => this.clearSelection());
        }

        // Clear tujuan button event
        if (this.elements.clearTujuan) {
            this.elements.clearTujuan.addEventListener('click', () => {
                if (this.elements.tujuanInput.disabled) return; // safety
                this.elements.tujuanInput.value = '';
                this.toggleFields();
                this.elements.tujuanInput.focus();
            });
        }

        // Bagian seksi search events
        this.elements.bagianSeksiSearch.addEventListener('input', (e) => this.handleSearchInput(e));
        this.elements.bagianSeksiSearch.addEventListener('focus', () => this.handleSearchFocus());

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => this.handleOutsideClick(e));
    }

    toggleClearButton() {
        if (!this.elements.clearButton) return;
        
        if (this.elements.bagianSeksiHidden.value !== '') {
            this.elements.clearButton.classList.remove('hidden');
        } else {
            this.elements.clearButton.classList.add('hidden');
        }
    }

    toggleTujuanClearButton(show) {
        if (!this.elements.clearTujuan) return;
        if (show) {
            this.elements.clearTujuan.classList.remove('hidden');
        } else {
            this.elements.clearTujuan.classList.add('hidden');
        }
    }

    clearSelection() {
        this.elements.bagianSeksiSearch.value = '';
        this.elements.bagianSeksiHidden.value = '';
        this.elements.unitKerjaDisplay.value = '';
        this.elements.unitKerjaHidden.value = '';
        this.elements.bagianSeksiDropdown.classList.add('hidden');
        this.toggleClearButton();
        this.toggleFields();
    }

    toggleFields() {
        const tujuanFilled = this.elements.tujuanInput.value.trim() !== '';
        const bagianSeksiSelected = this.elements.bagianSeksiHidden.value !== '';
        if (tujuanFilled) {
            // Disable bagian seksi search and reset selection
            this.elements.bagianSeksiSearch.disabled = true;
            this.elements.bagianSeksiSearch.value = '';
            this.elements.bagianSeksiHidden.value = '';
            this.elements.unitKerjaDisplay.value = '';
            this.elements.unitKerjaHidden.value = '';
            this.elements.bagianSeksiDropdown.classList.add('hidden');
            if (this.elements.clearButton) {
                this.elements.clearButton.classList.add('hidden');
            }

            // Style bagian seksi as disabled
            this.elements.bagianSeksiSearch.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');

            // Ensure tujuan input looks enabled (because user typed into it)
            this.elements.tujuanInput.disabled = false;
            this.elements.tujuanInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');

            // Show tujuan clear button
            this.toggleTujuanClearButton(true);
        } else if (bagianSeksiSelected) {
            // Disable tujuan input and style it as disabled (gray)
            this.elements.tujuanInput.disabled = true;
            this.elements.tujuanInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');

            // Ensure bagian seksi search appears enabled (normal styling)
            this.elements.bagianSeksiSearch.disabled = false;
            this.elements.bagianSeksiSearch.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');

            // Hide tujuan clear button when disabled
            this.toggleTujuanClearButton(false);
        } else {
            // Enable all and remove disabled styling
            this.elements.tujuanInput.disabled = false;
            this.elements.bagianSeksiSearch.disabled = false;
            this.elements.tujuanInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
            this.elements.bagianSeksiSearch.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');

            // Show tujuan clear button only if has value
            this.toggleTujuanClearButton(this.elements.tujuanInput.value.trim() !== '');
        }
    }

    renderDropdown(data) {
        this.elements.bagianSeksiDropdown.innerHTML = '';
        
        if (data.length === 0) {
            const noResult = document.createElement('div');
            noResult.className = 'px-4 py-2 text-gray-500 text-sm';
            noResult.textContent = 'Tidak ada hasil ditemukan';
            this.elements.bagianSeksiDropdown.appendChild(noResult);
        } else {
            data.forEach((bagian) => {
                const option = document.createElement('div');
                option.className = 'px-4 py-2 cursor-pointer text-sm border-b border-gray-100 last:border-b-0 dropdown-option';
                option.innerHTML = `
                    <div class="font-medium text-gray-900">${this.escapeHtml(bagian.bagian_seksi)}</div>
                    <div class="text-gray-500 text-xs">${this.escapeHtml(bagian.unit_kerja.unit_kerja)}</div>
                `;
                option.addEventListener('click', () => this.selectBagianSeksi(bagian));
                this.elements.bagianSeksiDropdown.appendChild(option);
            });
        }
    }

    selectBagianSeksi(bagian) {
        this.elements.bagianSeksiSearch.value = bagian.bagian_seksi;
        this.elements.bagianSeksiHidden.value = bagian.bagian_seksi_id;
        this.elements.unitKerjaDisplay.value = bagian.unit_kerja.unit_kerja;
        this.elements.unitKerjaHidden.value = bagian.unit_kerja.unit_kerja_id;
        this.elements.bagianSeksiDropdown.classList.add('hidden');
        this.toggleClearButton();
        this.toggleFields();
    }

    filterBagianSeksi(searchTerm) {
        const term = searchTerm.toLowerCase();
        this.filteredBagianSeksi = this.bagianSeksiData.filter((bagian) => {
            return bagian.bagian_seksi.toLowerCase().includes(term) ||
                   bagian.unit_kerja.unit_kerja.toLowerCase().includes(term);
        });
        this.renderDropdown(this.filteredBagianSeksi);
    }

    handleSearchInput(event) {
        const searchTerm = event.target.value.trim();
        if (searchTerm.length > 0) {
            this.filterBagianSeksi(searchTerm);
            this.elements.bagianSeksiDropdown.classList.remove('hidden');
        } else {
            this.elements.bagianSeksiDropdown.classList.add('hidden');
            // Clear hidden values when search is empty
            if (this.elements.bagianSeksiHidden.value !== '') {
                this.elements.bagianSeksiHidden.value = '';
                this.elements.unitKerjaDisplay.value = '';
                this.elements.unitKerjaHidden.value = '';
                this.toggleClearButton();
            }
        }
        this.toggleFields();
    }

    handleSearchFocus() {
        if (this.elements.bagianSeksiSearch.value.trim().length > 0) {
            this.elements.bagianSeksiDropdown.classList.remove('hidden');
        } else {
            // Show all options when focused
            this.renderDropdown(this.bagianSeksiData);
            this.elements.bagianSeksiDropdown.classList.remove('hidden');
        }
    }

    handleOutsideClick(event) {
        if (!this.elements.bagianSeksiSearch.contains(event.target) && 
            !this.elements.bagianSeksiDropdown.contains(event.target)) {
            this.elements.bagianSeksiDropdown.classList.add('hidden');
        }
    }

    initializeFormState() {
        // Initialize with existing values (for edit mode)
        const currentBagianSeksiId = this.elements.bagianSeksiHidden.value;
        if (currentBagianSeksiId) {
            const selectedBagian = this.bagianSeksiData.find(b => b.bagian_seksi_id == currentBagianSeksiId);
            if (selectedBagian) {
                this.elements.bagianSeksiSearch.value = selectedBagian.bagian_seksi;
                this.elements.unitKerjaDisplay.value = selectedBagian.unit_kerja.unit_kerja;
                this.elements.unitKerjaHidden.value = selectedBagian.unit_kerja.unit_kerja_id;
            }
        }

        // Initialize form state
        this.toggleClearButton();
        this.toggleFields();
    }

    // Utility function to escape HTML
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, (m) => map[m]);
    }

    // Public method to update bagian seksi data
    updateBagianSeksiData(data) {
        this.bagianSeksiData = data;
        this.filteredBagianSeksi = [...data];
    }

    // Public method to reset form
    resetForm() {
        this.clearSelection();
        this.elements.tujuanInput.value = '';
        this.elements.tujuanInput.disabled = false;
        this.elements.bagianSeksiSearch.disabled = false;
    }
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SuratKeluarForm;
}

// Make available globally
window.SuratKeluarForm = SuratKeluarForm;