import axios from "axios";


export class Record {

    init: Date;
    end: Date|null;

    constructor(raw: any){

        this.init = new Date(raw.init);
        this.end = raw.end ? new Date(raw.end) : null;

    }

}

export class WorkActivity {

    status: 'working' | 'stopped' = 'stopped';
    records: Record[] = [];

    constructor(){
        this.load();
    }

    async load(){

        const data = (await axios.get('/javi/list')).data;
        this.status = data.status;

        this.records = data.records.map((r:any) => new Record(r));


    }


    async start(){
        try {
            await axios.post('/javi/mark', {action: 'start'});
        } catch(e){
            alert("Error marking start")
        }

        await this.load();
    }

    async stop(){
        try {
            await axios.post('/javi/mark', {action: 'stop'});
        } catch(e){
            alert("Error marking stop")
        }

        await this.load();
    }


}