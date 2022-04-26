/*import axios from "axios";

export class Record2 {

    init: Date; 
    end: Date|null; 

    constructor(raw: any){

        this.init = new Date(raw.init);
        this.end = raw.end ? new Date(raw.end) : null;

    }
}


export class WorkActivity {

    status: 'recording' | 'stopped' = 'stopped';
    records: Record2[] = []; 

    constructor(){
        this.load();
    }


    async load(){
        const data = (await axios.get('/list')).data; 
        this.status = data.status; 

        this.records = data.records.map((r:any) => new Record2(r));
    }

    async start(){
        try{
            await axios.post('/mark', {action: 'start'});
        }catch(e){
            alert("Error marking start")
        }

        await this.load();
    }


    async stop(){
        try{
            await axios.post('/mark', {action: 'stop'});
        }catch(e){
            alert("Error marking stop")
        }

        await this.load();
    }
}*/

export default class Record {
    userId: string;
    deviceId: string;
    timestamp: Date;
    duration?: number;
    type: string;

    constructor(recordJson: any) {
        this.userId = recordJson.user_id;
        this.deviceId = recordJson.device_id;
        this.timestamp = recordJson.timestamp;
        this.type = recordJson.type;
    }
}


/*
import axios from "axios";


export default class Record {
    id: number;
    date: Date;
    record_type: string;

    constructor(recordJson: { [k: string]: any }) {
        this.id = recordJson.id;
        this.date = recordJson.date;
        this.record_type = recordJson.record_type;
    }

    async getRecordsIndex() {
        //return await axios.get("/api/proposal/receiver/" + this.id);
    }
}
*/
