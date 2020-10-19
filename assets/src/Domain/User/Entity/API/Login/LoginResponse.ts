import {Role} from "../../Types/Role";
import {Status} from "../../Types/Status";

export default interface LoginResponse{
    accessToken: string;
    refreshToken: string;
    role: Role;
    status: Status;
    email: string;
    id: string;
}