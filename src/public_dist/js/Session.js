export class Session {
    static formatNumber(value, precision) {
        return Number(value.toString())
            .toFixed(precision).toString()
            .replace('.', ',');
    }
    static formartDate(date) {
        return date;
    }
}
//# sourceMappingURL=Session.js.map