<script>
    const hardwareAllocations = @json($hardwareAllocations);
    const otherAllocations = @json($otherAllocations);

    const componentMap = {
        computer: "Computer",
        processor: "Processor",
        ram: "RAM",
        vga_card: "VGA Card",
        disk_drive1: "Disk Drive 1",
        disk_drive2: "Disk Drive 2",
        monitor: "Monitor",
    };

    // Element selector
    const hardwareFromLocation = document.getElementById('from_location_hardware');
    const otherFromLocation = document.getElementById('from_location_other');
    const fromDesk = document.getElementById('from_desk_number');
    const toLocation = document.getElementById('to_location');
    const toDesk = document.getElementById('to_desk_number');
    const otherItemSelect = document.getElementById('other_item');

    // Saat lokasi hardware diubah
    hardwareFromLocation?.addEventListener('change', () => {
        const locationId = hardwareFromLocation.value;
        updateDeskOptions(locationId, fromDesk);
    });

    // Saat lokasi other item diubah
    otherFromLocation?.addEventListener('change', () => {
        const locationId = otherFromLocation.value;

        if (!otherItemSelect) return;

        otherItemSelect.innerHTML = '<option value="" disabled selected>Select item</option>';

        const filtered = otherAllocations.filter(item => item.location_id == locationId);

        if (filtered.length === 0) {
            const option = document.createElement('option');
            option.text = 'No items available';
            option.disabled = true;
            otherItemSelect.appendChild(option);
            return;
        }

        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = item.others.id;
            option.text = `${item.others.name} (${item.quantity})`;
            otherItemSelect.appendChild(option);
        });
    });

    // Saat lokasi tujuan diubah
    toLocation?.addEventListener('change', () => {
        updateDeskOptions(toLocation.value, toDesk);
    });

    // Saat desk asal diubah
    fromDesk?.addEventListener('change', () => {
        populateComponentsFrom(fromDesk.value);
    });

    // Saat desk tujuan diubah
    toDesk?.addEventListener('change', () => {
        showComponentsTo(toDesk.value);
    });

    function updateDeskOptions(locationId, deskSelect) {
        deskSelect.innerHTML = `<option value="" selected disabled>Select desk</option>`;

        const filtered = hardwareAllocations.filter(h => h.location_id == locationId);
        const uniqueDesks = [...new Set(filtered.map(h => h.desk_number))];

        if (uniqueDesks.length === 0) {
            const opt = document.createElement('option');
            opt.text = 'No desks available';
            opt.disabled = true;
            deskSelect.appendChild(opt);
            return;
        }

        uniqueDesks.forEach(desk => {
            const option = document.createElement('option');
            option.value = desk;
            option.text = `Desk ${desk}`;
            deskSelect.appendChild(option);
        });
    }

    function populateComponentsFrom(deskNumber) {
        const container = document.getElementById('hardware_components_from');
        container.innerHTML = '';

        const selected = hardwareAllocations.find(h => h.desk_number == deskNumber && h.location_id == hardwareFromLocation.value);

        if (!selected) {
            container.innerHTML = '<p class="text-slate-500 italic col-span-2">No components found.</p>';
            return;
        }

        const checkAllDiv = document.createElement('div');
        checkAllDiv.className = 'col-span-2 mb-2';
        checkAllDiv.innerHTML = `
            <label class="inline-flex items-center">
                <input type="checkbox" id="check_all_components" class="mr-2 rounded border-slate-300">
                <span class="font-medium text-sm text-slate-700">Check All</span>
            </label>
        `;
        container.appendChild(checkAllDiv);

        for (const [key, label] of Object.entries(componentMap)) {
            const component = selected[key];
            const name = component?.name ?? 'Empty';
            const desc = component?.description ?? '';
            const display = desc ? `${name} (${desc})` : name;
            const disabled = !component;
            const textClass = disabled ? 'text-red-500 italic' : '';

            const div = document.createElement('div');
            div.innerHTML = `
                <label class="flex items-start">
                    <input type="checkbox" name="components_from[]" value="${key}" class="component-checkbox mr-2 mt-1 rounded border-slate-300" ${disabled ? 'disabled' : ''}>
                    <div class="flex w-full">
                        <span class="w-1/3">${label}</span>
                        <span class="${textClass}">: ${display}</span>
                    </div>
                </label>
            `;
            container.appendChild(div);
        }

        const checkAllBox = container.querySelector('#check_all_components');
        checkAllBox.addEventListener('change', function () {
            const checkboxes = container.querySelectorAll('.component-checkbox:not(:disabled)');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    function showComponentsTo(deskNumber) {
        const container = document.getElementById('hardware_components_to');
        container.innerHTML = '';

        const dummy = document.createElement('div');
        dummy.className = 'col-span-2 mb-2 h-[24px]';
        container.appendChild(dummy);

        const selected = hardwareAllocations.find(h => h.desk_number == deskNumber && h.location_id == toLocation.value);

        if (!selected) {
            container.innerHTML += '<p class="text-slate-500 italic col-span-2">No components found.</p>';
            return;
        }

        for (const [key, label] of Object.entries(componentMap)) {
            const component = selected[key];
            const display = component ? 'Filled' : 'Empty';
            const textClass = component ? 'text-red-500 italic' : '';

            const div = document.createElement('div');
            div.innerHTML = `
                <div class="flex items-start">
                    <span class="w-1/3">${label}</span>
                    <span class="${textClass}">: ${display}</span>
                </div>
            `;
            container.appendChild(div);
        }
    }
</script>
