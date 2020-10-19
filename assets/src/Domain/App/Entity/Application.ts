import {Device} from "./Types/Device";
import {Locale} from "./Types/Locale";
import {Menu} from "./Types/Menu";
import {RouteConfig} from "vue-router";

export class Application {
    private _device: Device;
    private _locale: Locale;
    private _menu: Menu;
    private readonly _logo: RouteConfig;

    constructor() {
        this._device = new Device();
        this._locale = new Locale();
        this._menu = new Menu();
        this._logo = { path: '/', name: 'logo', meta: { label: 'FlashBack', icon: 'mdi-home'}};
    }

    get logo(): RouteConfig {
        return this._logo;
    }

    get locale(): Locale {
        return this._locale;
    }

    set locale(value: Locale) {
        this._locale = value;
        return this;
    }

    get device(): Device {
        return this._device;
    }
    set device(value: Device) {
        this._device = value;
        return this;
    }

    get menu(): Menu {
        return this._menu;
    }

    set menu(value: Menu) {
        this._menu = value;
        return this;
    }
}