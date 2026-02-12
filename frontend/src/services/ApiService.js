import axios from "axios";

const baseDomain = "http://127.0.0.1:8080/api/";
const baseURL = `${baseDomain}`;

export default axios.create({
  baseURL,
  headers: {
    // "Authorization": "Bearer xxxxx"
  }
});
