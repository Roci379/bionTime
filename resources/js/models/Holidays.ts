export default class Holidays {
    userId: string;
    startingDate: Date;
    endDate: Date;
    type: string;

    constructor(holidayJson: any) {
        this.userId = holidayJson.user_id;
        this.startingDate = holidayJson.startingDate;
        this.endDate = holidayJson.endDate;
        this.type = holidayJson.type;
    }
}
