export function FormatterExameResultado(row) {
    let rowData = row.getData();
    let color;
    if (rowData.resultado === "AP") {
        color = "text-success";
    }
    else {
        color = "text-danger";
    }
    return `<span class="${color}">${rowData.resultado}</span>`;
}
//# sourceMappingURL=FormatterExameResultado.js.map