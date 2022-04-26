import axios from "axios";


export default class User {
    id: string;
    email: string;
    first_name: string;
    last_name: string;
    locations: Array<Location> | undefined;

    constructor(userJson: { [k: string]: any }) {
        this.id = userJson.id;
        this.email = userJson.email;
        this.first_name = userJson.first_name;
        this.last_name = userJson.last_name;

    }

/*     async getReceivedProposals() {
        return await axios.get("/api/proposal/receiver/" + this.id);
    } */
}
