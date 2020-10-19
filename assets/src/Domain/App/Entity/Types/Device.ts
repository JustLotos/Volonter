export class Device {
    public static MOBILE = 'MOBILE';
    public static DESKTOP = 'DESKTOP';

    private _type: string;

    constructor() {
        this._type = Device.DESKTOP;

        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            this._type = Device.MOBILE;
        }
    }

    get type(): string {
        return this._type;
    }

    set type(value: string) {
        this._type = value;
        return this;
    }
}