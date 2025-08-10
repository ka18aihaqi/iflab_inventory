document.addEventListener('DOMContentLoaded', function () {
    const itemTypeSelect = document.querySelector('select[name="category"]');

    if (!itemTypeSelect) return; // stop kalau select tidak ditemukan

    // lanjut jika ada
    const computerForm = document.getElementById('computer-form');
    const driveForm = document.getElementById('drive-form');
    const processorForm = document.getElementById('processor-form');
    const vgaForm = document.getElementById('vga-form');
    const ramForm = document.getElementById('ram-form');
    const monitorForm = document.getElementById('monitor-form');
    const otherForm = document.getElementById('other-form');

    function setDisabledFields(container, disabled) {
        container?.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = disabled;
        });
    }

    itemTypeSelect.addEventListener('change', function () {
        const selectedType = this.options[this.selectedIndex].text;

        [computerForm, driveForm, processorForm, vgaForm, ramForm, monitorForm, otherForm].forEach(form => {
            form?.classList.add('hidden');
            setDisabledFields(form, true);
        });

        const formsMap = {
            'Computer': computerForm,
            'Disk Drive': driveForm,
            'Processor': processorForm,
            'VGA': vgaForm,
            'RAM': ramForm,
            'Monitor': monitorForm,
            'Other': otherForm
        };

        const selectedForm = formsMap[selectedType];
        if (selectedForm) {
            selectedForm.classList.remove('hidden');
            setDisabledFields(selectedForm, false);
        }
    });

    itemTypeSelect.dispatchEvent(new Event('change'));
});
