import {injectable, singleton} from "tsyringe";
@injectable()
@singleton()
export class DateProvider{
    getDate(): Date {
        return new Date();
    }

    getDateHourZero(): Date {
        let date = new Date();
        date.setHours(0, 0, 0, 0);
        return date;
    }

    dateFrom(timestamp: number): Date {
        return new Date(timestamp);
    }

}