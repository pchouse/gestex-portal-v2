import { Util } from "./Util";
/**
 * Create the header menu to hide/show columns
 * @constructor
 */
// @ts-ignore
export var TableHeaderMenu = function () {
    let menu = [];
    // @ts-ignore
    let self = this;
    let columns = self.getColumns();
    menu.push({
        label: `<i gtx-role="refresh" class="bi bi-database-down" style="padding-right: 9px;"></i>Refrescar tabela`,
        action: function (e) {
            //prevent menu closing
            e.stopPropagation();
            self.redraw(true);
            // @ts-ignore
            self.setData();
        }
    });
    for (let column of columns) {
        if (Util.isEmpty(column.getDefinition().field)) {
            continue;
        }
        //create checkbox element
        let checkBox = document.createElement("input");
        checkBox.setAttribute("type", "checkbox");
        checkBox.classList.add("form-check-input");
        checkBox.checked = column.isVisible();
        checkBox.id = `ckb-${column.getDefinition().title}`;
        let labelCheckbox = document.createElement("label");
        labelCheckbox.classList.add("form-check-label");
        labelCheckbox.setAttribute("for", checkBox.id);
        labelCheckbox.innerText = column.getDefinition().title;
        let checkBoxContainer = document.createElement("div");
        checkBoxContainer.classList.add("form-check");
        checkBoxContainer.classList.add("form-switch");
        checkBoxContainer.appendChild(checkBox);
        checkBoxContainer.appendChild(labelCheckbox);
        //create menu item
        menu.push({
            label: checkBoxContainer,
            action: function (e) {
                //prevent menu closing
                e.stopPropagation();
                //toggle current column visibility
                column.toggle();
                checkBox.checked = column.isVisible();
                self.redraw(true);
            }
        });
    }
    setTimeout(() => {
        document.querySelectorAll('div.tabulator-menu').forEach((node) => {
            const top = node.getBoundingClientRect().top;
            if (top < 0) // Only align the menu with the top of the viewport when the menu is too long
             {
                // @ts-ignore
                node.style.top = 0;
                // @ts-ignore
                node.style.bottom = null;
            }
        });
    }, 0);
    return menu;
};
//# sourceMappingURL=TableHeaderMenu.js.map