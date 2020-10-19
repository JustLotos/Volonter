import en from "../../../../Plugins/I18n/Locales/EN/en";
import ru from "../../../../Plugins/I18n/Locales/RU/ru";

export class Locale {
    private _language: string;
    private static LANGUAGE: string = 'LANGUAGE';

    constructor() {
        this.language = <string>localStorage.getItem(Locale.LANGUAGE) || this.getLocaleList[0].value;
    }

    get getLocaleList(): Array<Object>{
        return [
            {value:'ru', label: "Русский"},
            {value:'en', label: "English"}
        ];
    }

    get getLocale(): string {
        const cookieLanguage = this._language;
        if (cookieLanguage) {
            return cookieLanguage;
        }

        const language = navigator.language.toLowerCase()
        const locales = Object.keys(messages)
        for (const locale of locales) {
            if (language.indexOf(locale) > -1) {
                return locale;
            }
        }

        return process.env.LOCALE;
    }

    get getMessages() {
        return {
            en,
            ru
        }
    }

    get language(): string {
        return this._language;
    }

    set language(value: string) {
        if (!this.getLocaleList.some((locale) => locale.value === value)) {
            throw 'Invalid locale value';
        }
        this._language = value;
        localStorage.setItem(Locale.LANGUAGE, value);

        return this;
    }
}