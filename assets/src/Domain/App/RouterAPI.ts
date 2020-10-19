import {RouteConfig} from "vue-router";

class RouterAPI {
    private $links: Array<RouteConfig>;
    private $linkAuthNotRequired: Array<RouteConfig>;

    constructor() {
        this.$links = [];
        this.$linkAuthNotRequired = [];
        if(localStorage.getItem('routes')) {
            this.$links = <Array<RouteConfig>>JSON.parse(<string>localStorage.getItem('routes'));
            this.$linkAuthNotRequired = this.$links.filter((link:RouteConfig) => !link.meta.auth);
        }
    }

    public getUrlByName(name: string): RouteConfig {
        let result = this.$links.filter((link: Link) => link.name === name).pop();
        if(!result) {
            throw `Url with name ${name} not found`;
        }
        return result;
    }

    public getUrlByPath(path: string): RouteConfig {
        let result = this.$links.filter((link: Link) => link.name === path).pop();
        if(!result) {
            throw `Url with path ${path} not found`;
        }
        return result;
    }

    public findUrlByName(name: string): RouteConfig | undefined {
        return this.$links.filter((link: Link) => link.name === name).pop();
    }
    public isNotAuthRequiredRoute(linkToCheck: RouteConfig): boolean {
        return this.$linkAuthNotRequired.some((link) => linkToCheck.name === link.name);
    }
}

export const RouterApi = new RouterAPI();