document.addEventListener("DOMContentLoaded", function () {
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // change status buttons
    document.querySelectorAll(".btn-change-status").forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const status = btn.dataset.status;
            fetch(`/admin/orders/${id}/status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify({ status }),
            })
                .then((r) => r.json())
                .then((resp) => {
                    if (!resp.success) alert(resp.message || "Failed");
                    else location.reload();
                })
                .catch((e) => alert("Error"));
        });
    });

    // save tracking
    const saveTrack = document.getElementById("save_tracking");
    if (saveTrack) {
        saveTrack.addEventListener("click", () => {
            const id = saveTrack.dataset.id;
            const tracking = document.getElementById("tracking_number").value;
            fetch(`/admin/orders/${id}/status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify({
                    status: "processing",
                    tracking_number: tracking,
                }),
            })
                .then((r) => r.json())
                .then((resp) => {
                    if (!resp.success) alert(resp.message || "Failed");
                    else
                        document.getElementById("order_status").innerText =
                            resp.status;
                });
        });
    }

    // save note
    const saveNote = document.getElementById("save_note");
    if (saveNote) {
        saveNote.addEventListener("click", () => {
            const id = saveNote.dataset.id;
            const note = document.getElementById("admin_note").value;
            fetch(`/admin/orders/${id}/add-note`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify({ note }),
            })
                .then((r) => r.json())
                .then((resp) => {
                    if (!resp.success) alert("Failed to save note");
                    else alert("Note saved");
                });
        });
    }

    // bulk actions
    const selectAll = document.getElementById("select_all");
    if (selectAll) {
        selectAll.addEventListener("change", () => {
            document
                .querySelectorAll(".bulk_checkbox")
                .forEach((cb) => (cb.checked = selectAll.checked));
        });
    }

    const runBulk = document.getElementById("run_bulk");
    if (runBulk) {
        runBulk.addEventListener("click", () => {
            const action = document.getElementById("bulk_action").value;
            const ids = Array.from(
                document.querySelectorAll(".bulk_checkbox:checked")
            ).map((i) => i.value);
            if (!action || ids.length === 0)
                return alert("Select action and orders");

            fetch(`/admin/orders/bulk`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify({ action, ids }),
            })
                .then((r) => r.json())
                .then((resp) => {
                    if (!resp.success) alert(resp.message || "Failed");
                    else location.reload();
                });
        });
    }
});
