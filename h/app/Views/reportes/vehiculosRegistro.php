<?php foreach ($vehiculos as $vehiculo): ?>
                            <tr>
                                <td>

                                    <select
                                        class="form-select"
                                        id="tipoDeVehiculo"
                                        name="tipo"
                                        onchange="actualizarTipoVehiculo(this.value, <?= $vehiculo['id'] ?>)"
                                        required>

                                        <?php foreach ($tipo_vehiculos as $tipoVehiculo): ?>

                                            <option value="<?= $tipoVehiculo['tipo'] ?>"
                                                <?= ($vehiculo['tipo'] == $tipoVehiculo['tipo']) ? 'selected' : '' ?>>

                                                <?= $tipoVehiculo['tipo'] ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </td>
                                <td><input
                                        type="text"
                                        class="form-control"
                                        id="placaDelVehiculo"
                                        name="placa"
                                        value="<?= $vehiculo['placa'] ?>"
                                        placeholder="Ej: ABC-123"
                                        required
                                        oninput="this.value = this.value.toUpperCase()"
                                        onchange="actualizarPlaca(this.value, <?= $vehiculo['id'] ?>)"></td>

                            </tr>
                        <?php endforeach ?>