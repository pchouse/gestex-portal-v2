import {RowComponent} from 'tabulator-tables';
import {TFacturaDetalhe} from "../MVC/Facturacao/js/TFacturaDetalhe";
import {TExame} from "../MVC/Exame/js/TExame";

export function FormatterExameResultado(row: RowComponent) {
    let rowData: TFacturaDetalhe | TExame = row.getData();
    let color: string;
    if (rowData.resultado === "AP") {
        color = "text-success";
    } else {
        color = "text-danger";
    }
    return `<span class="${color}">${rowData.resultado}</span>`
}
