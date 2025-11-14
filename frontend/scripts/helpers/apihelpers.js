import { apiRequest } from "../api/apiRequest.js";
export const loadClasses = async () => {
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
        console.log(response.data);
        return response.data;
    } catch (error) {
        console.error("Virhe aikojen lataamisessa:", error);
        throw error;
    }
}

export const loadLuokka = async (id) => {
    const payload = {
        method: "POST",
        Headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            getLuokatID: true,
            LuokkaID: id
        })

    };
    try {
        const response = await apiRequest("", payload);
        console.log(response.data);
        return response.data;
    } catch (error) {
        console.error("Virhe aikojen lataamisessa:", error);
        throw error;
    }
}

export const GetVarauksetByLuokka = async (id) => {
    const payload = {
        method: "POST",
        Headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            getVarauksetByLuokka: true,
            LuokkaID: id
        })

    };
    try {
        const response = await apiRequest("", payload);
        console.log(response.data);
        return response.data;
    } catch (error) {
        console.error("Virhe aikojen lataamisessa:", error);
        throw error;
    }
}

export async function lisaaVaraus(LuokkaID, Paivamaara, AloitusAika, LopetusAika, Tarkoitus) {
    const body = JSON.stringify({
        addVaraus: true,
        LuokkaID,
        Paivamaara,
        AloitusAika,
        LopetusAika,
        Tarkoitus,
    });

    try {
        const response = await apiRequest("", {
            method: "POST",
            body,
        });

        console.log("Varaus vastaus:", response);
        return response;
    } catch (error) {
        console.error("Virhe varauksen lisäämisessä:", error);
        throw error;
    }
}

export const getKayttajaVaraukset = async() => {
     const payload = {
        Headers: {
            "Content-Type": "application/json",
        },
    };
    try {
        const response = await apiRequest("?kayttajaVaraukset=true", payload);
        console.log(response.data);
        return response.data;
    } catch (error) {
        console.error("Virhe aikojen lataamisessa:", error);
        throw error;
    }
}

export const ping = async () => {
    const payload = {
        Headers: {
            "Content-Type": "application/json",
        },
    };
    try {
        const response = await apiRequest("?ping=true", payload);
        return response.data;
    } catch (error) {
        console.error("Virhe luokkien lataamisessa:", error);
        throw error;
    }
}