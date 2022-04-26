import axios from "axios";

import {Record} from "../javidemo/models";

export class ActivityList {

    status: 'start' | 'pause '| 'unpause' | 'stop' = 'stop'; 
    //records: Record[] = [];
    records = [];
    daily_records = [];
    time: string = '00:00:00';

    constructor() {
        this.load();
    }


    async load(){

        console.log("async load executed");

        const data = (await axios.get('/list')).data; 

        console.log(data);

        this.status = data.status; 

        //this.records = data.records.map((r:any)=> new Record(r));
        if(this.status !== 'stop'){
            this.time = data.time;
        } else{
            this.time = '00:00:00';
        }
       
    }


    async reload(){

        try {
            await axios.post('/buttonClicked', {action: 'change'});
        }catch(e){
            alert("Error reloading");
        }   

        await this.load();
    }

    async start(){
        try {
            await axios.post('/buttonClicked', {action: 'start'});
            const inputData = {recordState: 'start'}
            const response = await axios.post('/postRecord', inputData);
            const data = response.data;
            this.records = data.records;
            this.daily_records = data.daily_records;
            console.log("START");
            console.log(data);
            console.log("RECOOOOORDS 01"); 
            console.log(this.records);

        } catch(e){
            alert("Error marking start");
        }

        await this.load();
    }

    async pause(){
        try{
            await axios.post('/buttonClicked', {action: 'pause'});
            const inputData = {recordState: 'pause'}
            const response = await axios.post('/postRecord', inputData);
            const data = response.data;
            this.records = data.records;
            this.daily_records = data.daily_records; 
            console.log("PAUSE");
            console.log(data);
            
        } catch(e){
            alert("Error marking pause");
        }

        await this.load();
    }

    async unpause(){
        try {
            await axios.post('/buttonClicked', {action: 'unpause'});
            const inputData = {recordState: 'unpause'}
            const response = await axios.post('/postRecord', inputData);
            const data = response.data;
            this.records = data.records;
            console.log("UNPAUSE");
            console.log(data);

        } catch(e){
            alert("Error marking unpause");
        }

        await this.load();
    }

    async stop() {
        try{
            await axios.post('/buttonClicked', {action: 'stop'});
            const inputData = {recordState: 'stop'};
            const response = await axios.post('/postRecord', inputData);
            const data = response.data; 
            this.records = data.records;
            this.daily_records = data.daily_records;
            console.log("STOP");
            console.log(data);
            
        } catch(e){
            console.log((e as Error).message);
            alert("Error marking stop");
        }

        await this.load(); 
    }


}