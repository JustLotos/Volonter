<template>
    <v-layout align-center justify-center>
        <v-flex sm10 md8 lg6>
            <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Профиль пользоввтеля</v-toolbar-title>
            </v-toolbar>
            <v-row>
              <v-col>{{user.id}}</v-col>
              <v-col>{{user.email}}</v-col>
              <v-col>{{user.role}}</v-col>
              <v-col>{{user.status}}</v-col>
            </v-row>
        </v-flex>
    </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import User from "../Entity/User";
import {UserModule} from "../UserModule";

@Component({ components: {} })
export default class LoginPage extends Vue{
    user: User = UserModule.user;

    private handle() {
        // UserModule.login(payloads).then(() => {
        //     this.$router.push(AppModule.getRedirectToAuth);
        // }).catch((error: AxiosError)=>{
        //   console.log(error);
        //     if(error.response?.data.errors && error.response?.data.errors.auth) {
        //         this.notFound = !!error.response?.data.errors.auth;
        //     }
        //     console.log(error.toJSON);
        //     console.log(error.response);
        // });
    }
}
</script>