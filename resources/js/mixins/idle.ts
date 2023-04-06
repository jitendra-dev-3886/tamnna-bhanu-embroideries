import Vue from "vue";
import Component from "vue-class-component";
import { OnIdle } from "vue-plugin-helper-decorator";
import { UserModule } from "../store/user";

@Component
class Idle extends Vue {
    @OnIdle()
    public whenIdle(): void {
        UserModule.SET_DEFAULT_URL(window.location.pathname);
        UserModule.logoff("");
        this["$router"].push({ name: "Logoff" });
    }
}

export default Idle;
