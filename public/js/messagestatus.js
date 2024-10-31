window.addEventListener("load", init);

function init() {
    const selects = document.querySelectorAll(".status-modifier");

    selects.forEach((e) => {
        e.addEventListener("change", (e) => {
            if (e.target.value == 4) {
                e.target.parentElement.parentElement.parentElement.children[3].setAttribute("class", "row");
            } else {
                e.target.parentElement.parentElement.parentElement.children[3].setAttribute("class", "row visually-hidden");
            }
        });
    });
}
