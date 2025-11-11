import { apiRequest } from "../api/apiRequest.js";
export const loadClasses = async() => {
        const payload = {
            Headers: {
                "Content-Type": "application/json",
            },
        };
        try {
            const response = await apiRequest("?luokat=true", payload);
            return response.data;
        } catch (error) {
            console.error("Virhe luokkien lataamisessa:", error);
            throw error;
        }
}

export const loadTakenTimes = async () => {
    const payload = {
        Headers: {
            "Content-Type": "application/json",
        },
    };
    try {
        const response = await apiRequest("?varaukset=true", payload);
        return response.data;
    } catch (error) {
        console.error("Virhe aikojen lataamisessa:", error);
        throw error;
    }
}