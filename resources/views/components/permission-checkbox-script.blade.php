<script>
    (function () {
        function groupCheckboxes(group) {
            return document.querySelectorAll('.permission-checkbox[data-group="' + CSS.escape(group) + '"]');
        }

        function syncGroupState(group) {
            const groupToggle = document.querySelector('.select-group-permissions[data-group="' + CSS.escape(group) + '"]');
            if (!groupToggle) {
                return;
            }

            const items = groupCheckboxes(group);
            groupToggle.checked = items.length > 0 && Array.from(items).every((item) => item.checked);
        }

        function syncSelectAllState() {
            const selectAll = document.getElementById('select-all-permissions');
            const items = document.querySelectorAll('.permission-checkbox');
            if (!selectAll || items.length === 0) {
                return;
            }

            selectAll.checked = Array.from(items).every((item) => item.checked);
        }

        document.getElementById('select-all-permissions')?.addEventListener('change', function () {
            document.querySelectorAll('.permission-checkbox, .select-group-permissions').forEach((item) => {
                item.checked = this.checked;
            });
        });

        document.querySelectorAll('.select-group-permissions').forEach((groupToggle) => {
            groupToggle.addEventListener('change', function () {
                groupCheckboxes(this.dataset.group).forEach((item) => {
                    item.checked = this.checked;
                });
                syncSelectAllState();
            });
        });

        document.querySelectorAll('.permission-checkbox').forEach((item) => {
            item.addEventListener('change', function () {
                syncGroupState(this.dataset.group);
                syncSelectAllState();
            });
        });

        document.querySelectorAll('.select-group-permissions').forEach((groupToggle) => syncGroupState(groupToggle.dataset.group));
        syncSelectAllState();
    })();
</script>
