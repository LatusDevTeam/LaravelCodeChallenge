import Client from '@/services/ApiService';
const resource = '/jokes';

export default {
  async get(filters = {}) {
    const urlSearchParam = new URLSearchParams(filters);
    const response = await Client.get(`${resource}?${urlSearchParam.toString()}`);
    return response.data.data
  },
};
