import React, { useEffect } from "react";
import Modal from "@/Components/Modal";
import { useForm } from "@inertiajs/react";
import Button from "@/Components/Button";
import SelectInputRuang from "../Ruangan/SelectionInput";
import SelectInputPasien from "../Pasien/SelectionInput";
import SelectInputPetugas from "../Petugas/SelectionInput";
import { isEmpty } from "lodash";
import FormInputDate from "@/Components/FormInputDate";

export default function FormModalPeminjaman(props) {
    const { modalState } = props;
    const { data, setData, post, put, processing, errors, reset, clearErrors } =
        useForm({
            no_rm: null,
            kode_peminjaman: null,
            kode_ruang: null,
            tgl_pinjam: new Date(),
            is_back:false,
        });

    const handleOnChange = (event) => {
        setData(
            event.target.name,
            event.target.type === "checkbox"
                ? event.target.checked
                    ? 1
                    : 0
                : event.target.value
        );
    };

    const handleReset = () => {
        modalState.setData(null);
        reset();
        clearErrors();
    };

    const handleClose = () => {
        handleReset();
        modalState.toggle();
    };

    const handleSubmit = () => {
        const peminjaman = modalState.data;
        if (peminjaman !== null) {
            put(route("peminjaman.update", peminjaman), {
                onSuccess: () => handleClose(),
            });
            return;
        }
        post(route("peminjaman.store"), {
            onSuccess: () => handleClose(),
        });
    };

    useEffect(() => {
        const peminjaman = modalState.data;
        if (isEmpty(peminjaman) === false) {
            console.log(peminjaman);
            setData({
                no_rm: peminjaman?.pasien.id,
                kode_peminjaman: peminjaman?.petugas_pinjam.id,
                kode_ruang: peminjaman?.petugas_pinjam.ruang.id,
                tgl_pinjam: peminjaman?.tgl_pinjam,
            });
            return;
        }
    }, [modalState]);

    return (
        <Modal
            isOpen={modalState.isOpen}
            toggle={handleClose}
            title={"Pemijaman"}
        >
            <SelectInputPasien
                label="No RM Pasien"
                itemSelected={data.no_rm}
                onItemSelected={(id) => setData("no_rm", id)}
                error={errors.no_rm}
            />
            <SelectInputRuang
                label="Ruang"
                itemSelected={data.kode_ruang}
                onItemSelected={(id) => setData("kode_ruang", id)}
                error={errors.kode_ruang}
            />
            <SelectInputPetugas
                label="Petugas"
                itemSelected={data.kode_peminjaman}
                onItemSelected={(id) => setData("kode_peminjaman", id)}
                error={errors.kode_peminjaman}
                disabled={data.kode_ruang==null?true:false}
                kode_ruang={data.kode_ruang}
            />
            <FormInputDate
                name="tgl_pinjam"
                selected={data.tgl_pinjam}
                onChange={(date) => setData("tgl_pinjam", date)}
                label="Tanggal Pinjam"
                error={errors.tgl_pinjam}
            />

            <div className="flex items-center">
                <Button onClick={handleSubmit} processing={processing}>
                    Simpan
                </Button>
                <Button onClick={handleClose} type="secondary">
                    Batal
                </Button>
            </div>
        </Modal>
    );
}
