import { defineStore } from 'pinia';
import axios from 'axios';

export const useRoleStore = defineStore('role', {
  state: () => ({
    roles: [],
  }),
  actions: {
    async getAllRoles() {
      const response = await axios.get('/api/v1/admin/role');
      this.roles = response.data.roles;
      return this.roles;
    },
    async addRole(role) {
      const response = await axios.post('/api/v1/admin/role', role);
      this.roles.push(response.data);
    },
    async updateRole(roleId, updatedRoleData) {
      try {
        const response = await axios.put(`/api/v1/admin/role/${roleId}`, updatedRoleData);
        this.roles = this.roles.map(role =>
          role.id === roleId ? response.data : role
        );
      } catch (error) {
        console.error('Error updating role:', error);
        // يمكنك هنا التعامل مع الخطأ بشكل مناسب
      }
    },
    async deleteRole(roleId) {
        try{

            const register= await axios.delete(`/api/v1/admin/role/${roleId}`);
            console.log(register);
            this.roles = this.roles.filter(role => role.id !== roleId);
        }
        catch (error) {
            console.error('Error updating role:', error);
            // يمكنك هنا التعامل مع الخطأ بشكل مناسب
        }

    },

  },

});
