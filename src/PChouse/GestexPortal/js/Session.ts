export class Session{

    public static formatNumber(value: number|string, precision: number): string
    {
        return Number(value.toString())
            .toFixed(precision).toString()
            .replace('.', ',');
    }

    public static formartDate(date: string): string
    {
        return date;
    }

}