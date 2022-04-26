<template>
    <div id="bmk__reports">
        <div class="bmk__view reports__view">
            <leftbar :user="raw_user"></leftbar>
            
            <div class="reports__content bmk__view__content">
                <div class="reports__title">
                    <div class="reports__title__content"> 
                        <bar-chart-2-icon size="1.5x" class="menuIcon"></bar-chart-2-icon>
                        <p class="reports__text">reports</p>
                    </div>

                </div>

                <div class="reports__select__container">
                    <!--div class="selector select__groupby">
                        Group by: 
                        <select name="groupBy" class="reports__selector" id="groupBySelector">
                            <option class="selectorItem" v-for="option in options.groupby">
                                {{option}}
                            </option>
                        </select>
                    </div-->
                    <div class="selector select__filter__search">
                        Filter:
                        <input type="text" class="filter__input" id="inputSearch" @keyup="search" placeholder="Search by identifier or user...">
                    </div>
                    <!--div class="selector select__dateformat">
                        Date format: 
                        <select name="dateFormat" class="reports__selector" id="dateFOrmatSelector">
                            <option class="selectorItem" v-for="option in options.dateformat">
                                {{option}}
                            </option>
                        </select>
                     </div-->
                   
                    
                    <div class="reports__export">
                        <button @click="generateReport" class="reports__exportButton">Download all records</button>
                    </div>
                    <div class="selector select__exportformat">
                        Export: 
                        <select name="export" class="reports__selector" id="exportSelector">
                            <option class="selectorItem" v-for="(option,i) in options.exportformat" :key="i">
                                {{option}}
                            </option>
                        </select>
                    </div> 
                </div>
                <div class="reports__table">
                    REPORT REVIEW: 
                    <reporttable :info="info"></reporttable>
                </div>

                <div class="help__link" @click="launchHelp">
                    <p>Help</p>
                    <help-circle-icon size="2x" class="custom-class"></help-circle-icon>
                </div>

                <vue-html2pdf
                    :show-layout="false"
                    :float-layout="true"
                    :enable-download="true"
                    :preview-modal="true"
                    :paginate-elements-by-height="1400"
                    :pdf-quality="2"
                    :manual-pagination="false"
                    pdf-content-width="600px"
                    filename="usersReport"
                    pdf-format="a4"
                    pdf-orientation="landscape"

                    @hasStartedGeneration="hasStartedGeneration()"
                    @hasGenerated="hasGenerated($event)"
                    ref="html2Pdf"
                >
                    <section slot="pdf-content">
                        <reporttable :info="info"></reporttable>
                    </section>
                </vue-html2pdf>

            </div>
            <v-tour name="reportsTour" :steps="this.steps"></v-tour>
        </div>
    </div>
</template>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script-->
<!--script src="es6-promise.auto.min.js"></script-->
<!--script src="jspdf.min.js"></script-->
<!--script src="html2canvas.min.js"></script-->
<!--script src="html2pdf.min.js"></script-->

<script lang="ts">

import {Component, Prop, Vue} from 'vue-property-decorator';

import User from '../models/User';

import axios from "axios";

// Components 
import Leftbar from './UIBasics/Leftbar.vue';
import UpperMenu from './UIBasics/UpperMenu.vue';
import ReportTable from './UIBasics/ReportTable.vue';
import {HelpCircleIcon } from 'vue-feather-icons'

// Icons
import {PlusIcon} from 'vue-feather-icons';
import { BarChart2Icon } from 'vue-feather-icons';


import pdf from 'vue-pdf';
import VueHtml2pdf from 'vue-html2pdf';

//import jsPDF from 'jspdf'


// PDF and EXCEL
//import jsPDF from 'jspdf';





@Component({
    components: {
        PlusIcon, HelpCircleIcon,
        
        Leftbar, UpperMenu, ReportTable,

        BarChart2Icon,

        pdf, VueHtml2pdf
    }
})
export default class Reports extends Vue {

    @Prop(Object)
    raw_user!: {};


    @Prop()
    info!: any;

    //htmlToPdfOptions= { margin: [20,20,20,20], filename: 'usersReport.pdf' }

    dpitems: string[][] = [['TODAY', 'THIS WEEK', 'THIS MONTH'], ['Planned', 'Extra', 'Worked']]
    types = ['list', 'daily', 'weekly']

    options = {
        groupby: ["identifier", "user"],
        dateformat: ["list", "daily", "weekly"],
        exportformat: [".pdf", ".csv"]
    }

    $refs: any;

    steps = [
        {
            target: '#summary',
            content: 'Here you can access a summary of your daily activity.'
        },
        {
            target: '#timer',
            content: 'Here you can access your records.'
        },
        {
            target: '#calendar',
            content: 'Here you can see calendar of your events and other users events.'
        },
        {
            target: '#management',
            content: 'Here you can add, modify and delete other users.'
        },
        {
            target: '.profile',
            content: 'Here you can see your personal information.'
        },
        {
            target: '.select__filter__search',
            content: 'Filter by identifier or user value.'
        },
        {
            target: '.reports__exportButton',
            content: 'Export to pdf or csv file.'
        },
        {
            target: '.reportTable',
            content: 'Here you can see the records filtered.'
        }

    ]
  


    /*get user() {
        //return new User(this.raw_user);
    }*/

    launchHelp(){
        this.$tours['reportsTour'].start();
    }

    mounted() {
        //this.$tours['reportsTour'].start();
        //this.loadProposals();
    }

    getArrayData(){
        let arr: any = [];
        for(let i = 0; i<this.info.length; i++){
            arr.push(this.info[i][0]);
        }

        let new_arr: any = [];
        for(let j = 0; j<arr.length; j++){
            if(arr[j]['records']!='No records'){
                let iden = arr[j]['id'];
                let name = arr[j]['name'];
                let records = arr[j]['records'];

                for(let x = 0; x<records.length; x++){
                    let date = records[x]['date'];
                    let hours = records[x]['hours'];

                    for(let y=0; y<hours.length; y++){
                        let beginning = hours[y].beginning;
                        let end = hours[y].end;
                        let active = hours[y].working;
                        let total = hours[y].total; 


                        let json_arr = {'id': iden , 'name': name, 
                                        'date': date, 'beginning': beginning,
                                        'end': end, 'active': active, 
                                        'total': total};
                        new_arr.push(json_arr);
                    }
                }
               // let json_arr = {'id'=> arr[j]['id'], 'name'=>arr[j]['name'], 'date'=> arr[j]['records']['date']}
                
            }else{
                let iden = arr[j]['id'];
                let name = arr[j]['name'];
                let records = arr[j]['records'];
                let json_arr = {'id': iden , 'name': name, 
                                        'date': records, 'beginning': records,
                                        'end': records, 'active': records, 
                                        'total': records};
                new_arr.push(json_arr);
            }

        }
        return new_arr; 
    }


    generateReport () {
        var type = <HTMLInputElement>document.getElementById("exportSelector");
        if(type.value == ".pdf"){
            this.$refs.html2Pdf.generatePdf();
        }

        if(type.value ==".csv"){
            let csvContent = "data:text/csv;charset=utf-8,";
            let arrData = this.getArrayData();
            csvContent += [
                Object.keys(arrData[0]).join(";"),
                ...arrData.map((item: any) => Object.values(item).join(";"))
            ]
            .join("\n")
            .replace(/(^\[)|(\]$)/gm, "");

            const data = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", "export.csv");
            link.click();
        }
    }


    /*
    createPDF () {
        let pdfName = 'reports'; 
        //var element = <HTMLInputElement>document.getElementById('reportsTable');
        //html2pdf(element);
        //var doc = new jsPDF();
        //doc.text("Hello World", 10, 10);
        //doc.save(pdfName + '.pdf');
    }*/

    search(){

        var input, filter, table, tr, td0, td1, i, txtValue0, txtValue1;
        input = (<HTMLInputElement>document.getElementById("inputSearch"));
        filter = input.value.toUpperCase();
        //console.log(filter);
        table = (<HTMLInputElement>document.getElementById("reportsTable"));
        //console.log(table);
        tr = table.getElementsByTagName("tr");
        for(i=0; i<tr.length; i++){
            td0 = tr[i].getElementsByTagName("td")[0];
            td1 = tr[i].getElementsByTagName("td")[1];
            if(td0 || td1){
                txtValue0 = td0.textContent || td0.innerText;
                txtValue1 = td1.textContent || td1.innerText;
                if(txtValue0.toUpperCase().indexOf(filter)>-1 || txtValue1.toUpperCase().indexOf(filter)>-1){
                    tr[i].style.display="";
                }else{
                    tr[i].style.display = "none";
                }
            }
        }
    }

}

</script>

